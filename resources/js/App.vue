<template>
    <v-app>
        <v-app-bar app>
            <v-container>
                <v-toolbar-title>
                    <router-link
                        to="/"
                        style="text-decoration: none; color: inherit"
                    >
                        Приложение
                    </router-link>
                </v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn v-for="link in links" :key="link.name" :to="link.path">
                    {{ link.name }}
                </v-btn>
            </v-container>
        </v-app-bar>
        <v-main>
            <router-view />
        </v-main>
    </v-app>
</template>

<script>
export default {
    name: "App",
    data: () => ({
        links: [
            { name: "Purchases", path: "/purchases" }, // пример, подставьте ваши роуты
            // { name: 'Другой раздел', path: '/another' }, // и т.д.
        ],
    }),
};
</script>
