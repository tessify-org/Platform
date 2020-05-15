<template>
    <div id="request-email-access-button">
        
        <!-- Request access button -->
        <div v-if="showButton">
            <v-btn text small color="primary" :href="buttonHref">
                {{ buttonText }}
            </v-btn>
        </div>

        <!-- Request sent message -->
        <div v-if="!showButton">
            {{ sentText }}
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "hasSentRequest",
            "buttonHref",
            "buttonText",
            "sentText",
        ],
        data: () => ({
            tag: "[request-email-access-button]",
        }),
        computed: {
            showButton() {
                return this.hasSentRequest !== undefined && this.hasSentRequest !== null && !this.hasSentRequest;
            },
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" has sent request: ", this.hasSentRequest);
                console.log(this.tag+" button text: ", this.buttonText);
                console.log(this.tag+" button href: ", this.buttonHref);
                console.log(this.tag+" sent text: ", this.sentText);
            }
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #request-email-access-button {

    }
</style>