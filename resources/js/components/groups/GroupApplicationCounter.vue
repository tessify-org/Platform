<template>
    <span id="group-application-counter">
        ({{ mutableCount }})
    </span>
</template>

<script>
    import { EventBus } from './../../event-bus.js';
    export default {
        props: [
            "count",
        ],
        data: () => ({
            tag: "[group-application-counter]",
            mutableCount: 0,
        }),
        methods: {
            initialize() {
                // console.log(this.tag+" initializing");
                // console.log(this.tag+" count: ", this.count);
                this.initializeCount();
                this.startListening();
            },
            initializeCount() {
                this.mutableCount = parseInt(this.count);
            },
            startListening() {
                EventBus.$on("group-application-counter-down", function() {
                    this.mutableCount -= 1;
                }.bind(this));
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #group-application-counter {

    }
</style>