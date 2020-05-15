<template>
    <div id="review-form__wrapper">

        <!-- Form -->
        <div id="review-form" class="elevation-1">

            <!-- Rating -->
            <div class="form-field">
                <v-select
                    :label="strings.rating"
                    v-model="form.rating"
                    :items="ratingOptions"
                    :errors="hasErrors('rating')"
                    :error-messages="getErrors('rating')">
                </v-select>
                <input type="hidden" name="rating" :value="form.rating">
            </div>

            <!-- Message -->
            <div class="form-field">
                <v-textarea
                    name="message"
                    :label="strings.message"
                    v-model="form.message"
                    :placeholder="strings.message_hint"
                    :errors="hasErrors('message')"
                    :error-messages="getErrors('message')">
                </v-textarea>
            </div>

            <!-- Publicly share -->
            <div class="form-field checkbox">
                <v-checkbox
                    :label="strings.public"
                    v-model="form.public"
                    :errors="hasErrors('public')"
                    :error-messages="getErrors('public')">
                </v-checkbox>
                <input type="hidden" name="public" :value="form.public">
            </div>

        </div>

        <!-- Form controls -->
        <div class="form-controls">
            <div class="form-controls__left">
                <v-btn :href="backHref" outlined>
                    <i class="fas fa-arrow-left"></i>
                    {{ strings.cancel }}
                </v-btn>
            </div>
            <div class="form-controls__right">
                <v-btn type="submit" color="success">
                    <i class="fas fa-save"></i>
                    {{ strings.submit }}
                </v-btn>
            </div>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "review",
            "errors",
            "oldInput",
            "strings",
            "backHref",
        ],
        data: () => ({
            tag: "[review-form]",
            ratingOptions: [],
            form: {
                rating: 10,
                message: "",
                public: true,
            }
        }),
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" review: ", this.review);
                console.log(this.tag+" errors: ", this.errors);
                console.log(this.tag+" old input: ", this.oldInput);
                console.log(this.tag+" strings: ", this.strings);
                this.generateRatingOptions();
                this.initializeData();
            },
            initializeData() {
                if (this.review !== undefined && this.review !== null) {
                    this.form.rating = this.review.rating;
                    this.form.message = this.review.message;
                    this.form.public = this.review.public;
                }
                if (this.oldInput !== undefined && this.oldInput !== null) {
                    if (this.oldInput.rating !== null) this.form.rating = this.oldInput.rating;
                    if (this.oldInput.message !== null) this.form.message = this.oldInput.message;
                    if (this.oldInput.public !== null) this.form.public = this.oldInput.public === "true" ? true : false;
                }
            },
            generateRatingOptions() {
                this.ratingOptions.push({ text: "1/10", value: 1 });
                this.ratingOptions.push({ text: "2/10", value: 2 });
                this.ratingOptions.push({ text: "3/10", value: 3 });
                this.ratingOptions.push({ text: "4/10", value: 4 });
                this.ratingOptions.push({ text: "5/10", value: 5 });
                this.ratingOptions.push({ text: "6/10", value: 6 });
                this.ratingOptions.push({ text: "7/10", value: 7 });
                this.ratingOptions.push({ text: "8/10", value: 8 });
                this.ratingOptions.push({ text: "9/10", value: 9 });
                this.ratingOptions.push({ text: "10/10", value: 10 });
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
    #review-form__wrapper {
        #review-form {
            padding: 25px;
            border-radius: 3px;
            box-sizing: border-box;
            background-color: #fff;
        }
    }
</style>