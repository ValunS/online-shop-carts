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
                            @input="debounceFetchPurshases"
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
                        @click="fetchPurshases"
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
                    <PurshaseForm
                        @create="onCreatePurshase"
                        :purshase="selectedPurshase"
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
                    <PurshaseForm
                        @update="onUpdatePurshase"
                        :purshase="selectedPurshase"
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
                                :class="{ active: sortBy === 'purshase_date' }"
                                @click="changeSort('purshase_date')"
                            >
                                Дата
                                <v-icon
                                    v-if="sortBy === 'purshase_date'"
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
                        <tr v-for="purshase in purshases" :key="purshase.id">
                            <td>{{ purshase.store_name }}</td>
                            <td>{{ purshase.purshase_date }}</td>
                            <td>{{ purshase.sum }} {{ purshase.currency }}</td>
                            <td>
                                <a
                                    :href="purshase.document_path"
                                    target="_blank"
                                    >{{
                                        purshase.document_path
                                            ? purshase.document_path
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
                                        selectedPurshase = purshase;
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
                                    @click="deletePurshase(purshase.id)"
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
import PurshaseForm from "./PurshaseForm.vue";

export default {
    components: {
        PurshaseForm,
    },
    data() {
        return {
            purshases: [],
            showCreateForm: false,
            showEditForm: false,
            selectedPurshase: null,
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
        this.fetchPurshases();
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
        fetchPurshases() {
            this.$router.push({
                path: "/purshases",
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
                .get("/api/purshase", {
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
                    this.purshases = response.data.data;
                    this.totalPages = response.data.meta.last_page;
                })
                .catch((error) => {
                    console.error(
                        "Ошибка при получении списка покупок:",
                        error
                    );
                });
        },
        onCreatePurshase(newPurshase) {
            this.purshases.push(newPurshase);
            this.showCreateForm = false;
        },
        onUpdatePurshase(updatedPurshase) {
            const index = this.purshases.findIndex(
                (p) => p.id === updatedPurshase.id
            );
            if (index !== -1) {
                this.purshases.splice(index, 1, updatedPurshase);
            }
            this.showEditForm = false;
        },
        deletePurshase(id) {
            if (confirm("Вы уверены, что хотите удалить эту покупку?")) {
                axios
                    .delete(`/api/purshase/${id}`)
                    .then(() => {
                        this.fetchPurshases();
                    })
                    .catch((error) => {
                        console.error("Ошибка при удалении покупки:", error);
                    });
            }
        },
        debounceFetchPurshases() {
            clearTimeout(this.debounceTimeout);
            this.debounceTimeout = setTimeout(() => {
                this.fetchPurshases();
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
            this.fetchPurshases();
        },
        updatePagination() {
            this.fetchPurshases();
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
