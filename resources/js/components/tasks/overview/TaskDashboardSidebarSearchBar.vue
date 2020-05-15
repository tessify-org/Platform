<template>
    <div id="task-dashboard-sidebar-search-bar">

        <v-text-field
            solo
            :label="title"
            v-model="form.query">
        </v-text-field>

    </div>
</template>

<script>
    import { EventBus } from './../../../event-bus.js';
    export default {
        props: [
            "title",
        ],
        data: () => ({
            tag: "[task-dashboard-sidebar-search-bar]",
            form: {
                query: "",
            }
        }),
        watch: {
            "form.query": function() {
                EventBus.$emit("task-dashboard__search-query", this.form.query);
            }
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" title: ", this.title);
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #task-dashboard-sidebar-search-bar {

    }
</style>