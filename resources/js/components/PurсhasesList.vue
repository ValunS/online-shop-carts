<template>
    <div>
        <v-container>
            <v-card class="pa-4 mb-4">
                <v-row>
                    <v-col cols="12">
                        <v-text-field
                            v-model="searchTerm"
                            label="Поиск"
                            append-icon="mdi-magnify"
                            @input="debounceFetchPurchases"
                            density="compact"
                        ></v-text-field>
                    </v-col>
                </v-row>

                <v-row>
                    <v-col cols="12" md="6">
                        <v-select
                            v-model="selectedStore"
                            :items="storeOptions"
                            item-title="name"
                            item-value="id"
                            label="Магазин"
                            density="compact"
                        ></v-select>
                    </v-col>
                    <v-col cols="12" md="6">
                        <v-select
                            v-model="selectedCurrency"
                            :items="currencyOptions"
                            item-title="name"
                            item-value="id"
                            label="Валюта"
                            density="compact"
                        ></v-select>
                    </v-col>
                </v-row>

                <v-row justify="end">
                    <v-btn
                        color="primary"
                        class="mr-2"
                        @click="fetchPurchases"
                        density="compact"
                    >
                        Отфильтровать
                    </v-btn>
                    <v-btn
                        color="success"
                        @click="showCreateForm = true"
                        density="compact"
                    >
                        Добавить
                    </v-btn>
                </v-row>
            </v-card>
        </v-container>

        <v-dialog v-model="showCreateForm" max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Создать покупку</span>
                </v-card-title>
                <v-card-text>
                    <PurchaseForm
                        @create="onCreatePurchase"
                        :purchase="selectedPurchase"
                        :stores="stores"
                        :currencies="currencies"
                    />
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="showCreateForm = false"
                    >
                        Закрыть
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>

        <v-dialog v-model="showEditForm" max-width="600px">
            <v-card>
                <v-card-title>
                    <span class="text-h5">Редактировать покупку</span>
                </v-card-title>
                <v-card-text>
                    <PurchaseForm
                        @update="onUpdatePurchase"
                        :purchase="selectedPurchase"
                        :stores="stores"
                        :currencies="currencies"
                    />
                </v-card-text>
                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn
                        color="blue darken-1"
                        text
                        @click="showEditForm = false"
                    >
                        Закрыть
                    </v-btn>
                </v-card-actions>
            </v-card>
        </v-dialog>
        <v-container>
            <v-card>
                <v-table class="pa-4">
                    <thead>
                        <tr>
                            <th
                                class="text-left"
                                :class="{ active: sortBy === 'store_name' }"
                                @click="changeSort('store_name')"
                            >
                                Магазин
                                <v-icon
                                    v-if="sortBy === 'store_name'"
                                    small
                                    :icon="
                                        sortOrder === 'asc'
                                            ? 'mdi-arrow-up'
                                            : 'mdi-arrow-down'
                                    "
                                ></v-icon>
                            </th>
                            <th
                                class="text-left"
                                :class="{ active: sortBy === 'purchase_date' }"
                                @click="changeSort('purchase_date')"
                            >
                                Дата
                                <v-icon
                                    v-if="sortBy === 'purchase_date'"
                                    small
                                    :icon="
                                        sortOrder === 'asc'
                                            ? 'mdi-arrow-up'
                                            : 'mdi-arrow-down'
                                    "
                                ></v-icon>
                            </th>
                            <th
                                class="text-left"
                                :class="{ active: sortBy === 'sum' }"
                                @click="changeSort('sum')"
                            >
                                Сумма
                                <v-icon
                                    v-if="sortBy === 'sum'"
                                    small
                                    :icon="
                                        sortOrder === 'asc'
                                            ? 'mdi-arrow-up'
                                            : 'mdi-arrow-down'
                                    "
                                ></v-icon>
                            </th>
                            <th class="text-left">Документ</th>
                            <th class="text-left">Действие</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="purchase in purchases" :key="purchase.id">
                            <td>{{ purchase.store_name }}</td>
                            <td>{{ purchase.purchase_date }}</td>
                            <td>{{ purchase.sum }} {{ purchase.currency }}</td>
                            <td>
                                <a
                                    :href="purchase.document_path"
                                    target="_blank"
                                    >{{
                                        purchase.document_path
                                            ? purchase.document_path
                                                  .split("/")
                                                  .pop()
                                            : ""
                                    }}</a
                                >
                            </td>
                            <td>
                                <v-btn
                                    color="primary"
                                    size="small"
                                    variant="outlined"
                                    class="w-100"
                                    @click="
                                        selectedPurchase = purchase;
                                        showEditForm = true;
                                    "
                                >
                                    Редактировать
                                </v-btn>
                                <v-btn
                                    color="error"
                                    size="small"
                                    variant="outlined"
                                    class="w-100"
                                    @click="deletePurchase(purchase.id)"
                                >
                                    Удалить
                                </v-btn>
                            </td>
                        </tr>
                    </tbody>
                </v-table>
                <v-pagination
                    v-model="currentPage"
                    :length="totalPages"
                    @update:model-value="updatePagination"
                    class="mt-4"
                ></v-pagination>
            </v-card>
        </v-container>
    </div>
</template>

<script>
import axios from "axios";
import PurchaseForm from "./PurсhaseForm.vue";

export default {
    components: {
        PurchaseForm,
    },
    data() {
        return {
            purchases: [],
            showCreateForm: false,
            showEditForm: false,
            selectedPurchase: null,
            searchTerm: "",
            stores: [],
            selectedStore: null,
            currencies: [],
            selectedCurrency: null,
            debounceTimeout: null,
            currentPage: 1,
            totalPages: 1,
            sortBy: null,
            sortOrder: "asc",
        };
    },
    mounted() {
        this.fetchPurchases();
        this.fetchStores();
        this.fetchCurrencies();

        // Получение параметров из URL
        const queryParams = this.$route.query;
        this.searchTerm = queryParams.q || "";
        this.selectedStore = queryParams.store_id || null;
        this.selectedCurrency = queryParams.currency || null;
        this.currentPage = parseInt(queryParams.page) || 1;
        this.sortBy = queryParams.sortBy || null;
        this.sortOrder = queryParams.sortOrder || "asc";
    },
    methods: {
        fetchStores() {
            axios
                .get("/api/stores")
                .then((response) => {
                    this.stores = response.data.data;
                })
                .catch((error) => {
                    console.error(
                        "Ошибка при получении списка магазинов:",
                        error
                    );
                });
        },
        fetchCurrencies() {
            axios
                .get("/api/currencies")
                .then((response) => {
                    this.currencies = response.data.data;
                })
                .catch((error) => {
                    console.error("Ошибка при получении списка валют:", error);
                });
        },
        fetchPurchases() {
            this.$router.push({
                path: "/purchases",
                query: {
                    page: this.currentPage,
                    q: this.searchTerm,
                    store_id: this.selectedStore,
                    currency: this.selectedCurrency,
                    sortBy: this.sortBy,
                    sortOrder: this.sortOrder,
                },
            });

            axios
                .get("/api/purchase", {
                    params: {
                        page: this.currentPage,
                        q: this.searchTerm,
                        store_id: this.selectedStore,
                        currency: this.selectedCurrency,
                        sortBy: this.sortBy,
                        sortOrder: this.sortOrder,
                    },
                })
                .then((response) => {
                    this.purchases = response.data.data;
                    this.totalPages = response.data.meta.last_page;
                })
                .catch((error) => {
                    console.error(
                        "Ошибка при получении списка покупок:",
                        error
                    );
                });
        },
        onCreatePurchase(newPurchase) {
            this.purchases.push(newPurchase);
            this.showCreateForm = false;
        },
        onUpdatePurchase(updatedPurchase) {
            const index = this.purchases.findIndex(
                (p) => p.id === updatedPurchase.id
            );
            if (index !== -1) {
                this.purchases.splice(index, 1, updatedPurchase);
            }
            this.showEditForm = false;
        },
        deletePurchase(id) {
            if (confirm("Вы уверены, что хотите удалить эту покупку?")) {
                axios
                    .delete(`/api/purchase/${id}`)
                    .then(() => {
                        this.fetchPurchases();
                    })
                    .catch((error) => {
                        console.error("Ошибка при удалении покупки:", error);
                    });
            }
        },
        debounceFetchPurchases() {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.fetchPurchases();
            }, 300); // Задержка в миллисекундах
        },
        changeSort(field) {
            if (this.sortBy === field) {
                this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
            } else {
                this.sortBy = field;
                this.sortOrder = "asc";
            }
            this.currentPage = 1; // Сбрасываем страницу на первую при смене сортировки
            this.fetchPurchases();
        },
        updatePagination() {
            this.fetchPurchases();
        },
    },
    computed: {
        storeOptions() {
            return [
                { id: null, name: "ALL" },
                ...this.stores.map((store) => ({
                    id: store.id,
                    name: store.name,
                })),
            ];
        },
        currencyOptions() {
            return [
                { id: null, name: "ALL" },
                ...this.currencies.map((currency) => ({
                    id: currency,
                    name: currency,
                })),
            ];
        },
    },
};
</script>
