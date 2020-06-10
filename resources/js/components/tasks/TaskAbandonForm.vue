<template>
    <div id="task-abandon-form" class="elevation-1">
        
        <!-- Header -->
        <div id="task-abandon-form__header">
            <div id="header-bg" :style="{ backgroundImage: 'url('+task.header_image_url+')' }"></div>
            <div id="header-bg-overlay"></div>
            <div id="header-text">
                <h2 id="header-text__title">{{ task.title[locale] }}</h2>
            </div>
        </div>
        
        <!-- Content -->
        <div id="task-abandon-form__content">
            
            <!-- Text -->
            <div id="abandon-text">{{ strings.text }}</div>

            <!-- Reason -->
            <div class="form-field">
                <v-textarea
                    v-model="form.reason"
                    :label="strings.reason"
                    :placeholder="strings.reason_placeholder"
                    :errors="hasErrors('reason')"
                    :error-messages="getErrors('reason')">
                </v-textarea>
            </div>

        </div>
        
        <!-- Controls -->
        <div id="task-abandon-form__actions">
            <div id="task-abandon-form__actions-left">
                <v-btn :href="backHref" outlined>
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.cancel }}
                </v-btn>
            </div>
            <div id="task-abandon-form__actions-right">
                <v-btn type="submit" color="red" dark depressed>
                    <i class="fas fa-trash-alt"></i>
                    {{ strings.submit }}
                </v-btn>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "task",
            "strings",
            "errors",
            "oldInput",
            "backHref",
            "locale",
        ],
        data: () => ({
            tag: "[task-abandon-form]",
            form: {
                reason: "",
            },
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" task: ", this.task);
                console.log(this.tag+" strings: ", this.strings);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" back href: ", this.backHref);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" locale: ", this.locale);
                this.initializeData();
            },
            initializeData() {
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.reason !== null) this.form.reason = this.oldInput.reason;
                }
            },
            hasErrors(field) {
                if (this.errors !== undefined && this.errors !== null && this.errors.length > 0) {
                    if (this.errors[field] !==  undefined &&  this.errors[field] !== "") {
                        return true;
                    }
                }
                return false;
            },
            getErrors(field) {
                if (this.errors !== undefined && this.errors !== null && this.errors[field] !== undefined && this.errors[field] !== null) {
                    return this.errors[field];
                }
                return [];
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #task-abandon-form {
        width: 600px;
        margin: 0 auto;
        overflow: hidden;
        border-radius: 3px;
        background-color: #ffffff;
        #task-abandon-form__header {
            width: 100%;
            height: 200px;
            position: relative;
            #header-bg {
                top: 0;
                left: 0;
                z-index: 1;
                width: 100%;
                height: 100%;
                position: absolute;
                background-size: cover;
                background-repeat: no-repeat;
                background-position: center center;
            }
            #header-bg-overlay {
                top: 0;
                left: 0;
                z-index: 2;
                width: 100%;
                height: 100%;
                position: absolute;
                background-color: rgba(255, 255, 255, .5);
            }
            #header-text {
                top: 0;
                left: 0;
                z-index: 3;
                width: 100%;
                height: 100%;
                padding: 25px;
                display: flex;
                position: absolute;
                align-items: center;
                box-sizing: border-box;
                flex-direction: column;
                justify-content: center;
                #header-text__title {
                    font-size: 2em;
                    text-align: center;
                    line-height: 1.2em;
                }
            }
        }
        #task-abandon-form__content {
            box-sizing: border-box;
            padding: 30px 30px 20px 30px;
            #abandon-text {
                margin: 0 0 15px 0;
            }
        }
        #task-abandon-form__actions {
            display: flex;
            flex-direction: row;
            align-items: center;
            padding: 20px 25px;
            box-sizing: border-box;
            background-color: hsl(0, 0%, 95%);
            #task-abandon-form__actions-left {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
            }
            #task-abandon-form__actions-right {
                flex: 1;
                display: flex;
                flex-direction: row;
                align-items: center;
                justify-content: flex-end;
            }
        }
    }
</style>