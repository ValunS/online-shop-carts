<template>
    <form @submit.prevent="handleSubmit">
        <v-container>
            <v-row>
                <v-col cols="12" md="6">
                    <v-select
                        v-if="localStores.length"
                        v-model.number="form.store_id"
                        :items="localStores"
                        item-title="name"
                        item-value="id"
                        label="Магазин"
                        required
                        density="compact"
                    ></v-select>
                </v-col>
                <v-col cols="12" md="6">
                    <v-text-field
                        v-model="form.purchase_date"
                        label="Дата"
                        type="datetime-local"
                        required
                        density="compact"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                    <v-text-field
                        v-model.number="form.sum"
                        label="Сумма"
                        required
                        density="compact"
                    ></v-text-field>
                </v-col>

                <v-col cols="12" md="6">
                    <v-select
                        v-if="localCurrencies.length"
                        v-model="form.currency"
                        :items="localCurrencies"
                        label="Валюта"
                        required
                        density="compact"
                    ></v-select>
                </v-col>

                <v-col cols="12" md="6">
                    <v-file-input
                        label="Документ"
                        @change="handleFileChange"
                        density="compact"
                    ></v-file-input>
                </v-col>
            </v-row>

            <v-row justify="end">
                <v-btn
                    type="submit"
                    color="primary"
                    density="compact"
                    :loading="loading"
                    >{{ isEdit ? "Обновить" : "Создать" }}</v-btn
                >
                <v-btn
                    color="error"
                    density="compact"
                    variant="outlined"
                    class="ml-2"
                    @click="$emit('close')"
                >
                    Отмена
                </v-btn>
            </v-row>
        </v-container>
    </form>
</template>

<script>
import axios from "axios";

export default {
    props: {
        purchase: {
            type: Object,
            default: null,
        },
        stores: {
            type: Array,
            default: null,
        },
        currencies: {
            type: Array,
            default: null,
        },
    },
    data() {
        return {
            localStores: this.stores ? [...this.stores] : [],
            localCurrencies: this.currencies ? [...this.currencies] : [],
            form: {
                store_id: this.purchase?.store_id || "",
                purchase_date: this.purchase?.purchase_date || "",
                sum: this.purchase?.sum || "",
                currency: this.purchase?.currency || "",
                document: null,
            },
            loading: false,
        };
    },
    computed: {
        isEdit() {
            return !!this.purchase;
        },
    },
    mounted() {
        if (!this.stores) {
            this.fetchStores();
        }
        if (!this.currencies) {
            this.fetchCurrencies();
        }
    },
    methods: {
        fetchStores() {
            axios
                .get("/api/stores")
                .then((response) => {
                    this.localStores = response.data.data;
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
                    this.localCurrencies = response.data.data;
                })
                .catch((error) => {
                    console.error("Ошибка при получении списка валют", error);
                });
        },
        handleFileChange(event) {
            this.form.document = event.target.files[0];
        },
        handleSubmit() {
            this.loading = true;

            const formData = new FormData();
            formData.append("store_id", this.form.store_id);
            formData.append("purchase_date", this.form.purchase_date);
            formData.append("sum", this.form.sum);
            formData.append("currency", this.form.currency.toLowerCase());
            if (this.form.document) {
                formData.append("document", this.form.document);
            }

            if (this.isEdit) {
                this.updatePurchase(formData);
            } else {
                this.createPurchase(formData);
            }
        },
        createPurchase(formData) {
            axios
                .post("/api/purchase", formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    this.$emit("create", response.data.data);
                    this.$emit("close");
                })
                .catch((error) => {
                    console.error("Ошибка при создании покупки:", error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
        updatePurchase(formData) {
            axios
                .put(`/api/purchase/${this.purchase.id}`, formData, {
                    headers: {
                        "Content-Type": "multipart/form-data",
                    },
                })
                .then((response) => {
                    this.$emit("update", response.data.data);
                    this.$emit("close");
                })
                .catch((error) => {
                    console.error("Ошибка при обновлении покупки:", error);
                })
                .finally(() => {
                    this.loading = false;
                });
        },
    },
};
</script>
