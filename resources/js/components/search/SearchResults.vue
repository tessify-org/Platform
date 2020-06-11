<template>
    <div id="search-results__wrapper">

        <!-- Controls -->
        <div id="search-controls">
            <div id="search-controls__left">

                <!-- Search bar -->
                <div id="search-bar">
                    <div id="search-bar__input">
                        <v-text-field
                            solo
                            hide-details
                            v-model="form.query"
                            :placeholder="strings.search_placeholder">
                        </v-text-field>
                    </div>
                    <div id="search-bar__num-results" v-if="mutableResults.length > 0">
                        {{ numResultsText }}
                    </div>
                </div>

            </div>
            <div id="search-controls__right">

                <!-- Tags -->
                <v-btn color="white" :href="tagOverviewHref">
                    <i class="fas fa-tag"></i>
                    {{ strings.view_tags }}
                </v-btn>

            </div>
        </div>

        <!-- Results -->
        <div id="search-results" class="elevation-1">
            <div class="search-result__wrapper" v-for="(result, ri) in paginatedResults" :key="ri">
                <!-- User result -->
                <a :href="result.entry.view_href" class="search-result" v-if="result.type === 'user'">
                    <div class="search-result__score">{{ result.score }}</div>
                    <div class="search-result__type-wrapper">
                        <div class="search-result__type">{{ strings.user_type }}</div>
                    </div>
                    <div class="search-result__content">
                        <div class="result-title">{{ result.entry.first_name+" "+result.entry.last_name }}</div>
                        <div class="result-text">{{ result.entry.headline }}</div>
                    </div>
                </a>
                <!-- Task result -->
                <a :href="result.entry.view_href" class="search-result" v-if="result.type === 'task'">
                    <div class="search-result__score">{{ result.score }}</div>
                    <div class="search-result__type-wrapper">
                        <div class="search-result__type">{{ strings.task_type }}</div>
                    </div>
                    <div class="search-result__content">
                        <div class="result-title">{{ result.entry.title }}</div>
                        <div class="result-text">{{ result.entry.description[locale] }}</div>
                    </div>
                </a>
                <!-- Project result -->
                <a :href="result.entry.view_href" class="search-result" v-if="result.type === 'project'">
                    <div class="search-result__score">{{ result.score }}</div>
                    <div class="search-result__type-wrapper">
                        <div class="search-result__type">{{ strings.project_type }}</div>
                    </div>
                    <div class="search-result__content">
                        <div class="result-title">{{ result.entry.title }}</div>
                        <div class="result-text">{{ result.entry.description[locale] }}</div>
                    </div>
                </a>
                <!-- Ministry result -->
                <a :href="result.entry.view_href" class="search-result" v-if="result.type === 'ministry'">
                    <div class="search-result__score">{{ result.score }}</div>
                    <div class="search-result__type-wrapper">
                        <div class="search-result__type">{{ strings.ministry_type }}</div>
                    </div>
                    <div class="search-result__content">
                        <div class="result-title">{{ result.entry.name[locale] }}</div>
                        <div class="result-text">{{ result.entry.description[locale] }}</div>
                    </div>
                </a>
                <!-- Organization result -->
                <a :href="result.entry.view_href" class="search-result" v-if="result.type === 'organization'">
                    <div class="search-result__score">{{ result.score }}</div>
                    <div class="search-result__type-wrapper">
                        <div class="search-result__type">{{ strings.organization_type }}</div>
                    </div>
                    <div class="search-result__content">
                        <div class="result-title">{{ result.entry.name[locale] }}</div>
                        <div class="result-text">{{ result.entry.description[locale] }}</div>
                    </div>
                </a>
            </div>
        </div>

        <!-- No results -->
        <div id="no-results" class="elevation-1" v-if="paginatedResults.length === 0 && searched && !loading">
            {{ strings.no_results }}
        </div>

        <!-- No results because of missing query -->
        <div id="no-results" class="elevation-1" v-if="paginatedResults.length === 0 && !searched && !loading">
            {{ strings.enter_query }}
        </div>
        
        <!-- Loading -->
        <div id="loading" class="elevation-1" v-if="loading">
            <div id="loading-icon">
                <i class="fa fa-spinner fa-spin"></i>
            </div>
        </div>
        
        <!-- Pagination -->
        <div id="pagination" v-if="numPaginatedPages > 1">
            <v-pagination
                v-model="pagination.currentPage"
                :length="numPaginatedPages"
                total-visible="10">
            </v-pagination>
        </div>

    </div>
</template>

<script>
    export default {
        props: [
            "results",
            "searchQuery",
            "apiEndpoint",
            "strings",
            "locale",
            "tagOverviewHref",
        ],
        data: () => ({
            tag: "[search-results]",
            mutableResults: [],
            filters: {

            },
            filteredResults: [],
            pagination: {
                perPage: 10,
                currentPage: 1,
            },
            paginatedResults: [],
            form: {
                query: "",
            },
            searched: false,
            loading: false,
        }),
        computed: {
            numPaginatedPages() {
                return Math.ceil(this.filteredResults.length/this.pagination.perPage);
            },
            numResultsText() {
                if (this.mutableResults.length == 1) {
                    return this.mutableResults.length+" "+this.strings.result_found;
                } else {
                    return this.mutableResults.length+" "+this.strings.results_found;
                }
            },
        },
        watch: {
            "filters": {
                deep: true,
                handler: function() {
                    this.filter();
                }
            },
            "pagination.currentPage": function() {
                this.paginate();
            },
            "form.query": _.debounce(function() {
                if (!this.searched) this.searched = true;
                this.search();
            }, 250),
        },
        methods: {
            initialize() {
                console.log(this.tag+" initializing");
                console.log(this.tag+" query: ", this.query);
                console.log(this.tag+" results: ", this.results);
                console.log(this.tag+" api endpoint: ", this.apiEndpoint);
                console.log(this.tag+" strings: ", this.strings);
                this.initializeData();
            },
            initializeData() {
                if (this.searchQuery !== undefined && this.searchQuery !== null && this.searchQuery !== "") {
                    this.form.query = this.searchQuery;
                }
                if (this.results !== undefined && this.results !== null && this.results.length > 0) {
                    for (let i = 0; i < this.results.length; i++){
                        this.mutableResults.push(this.results[i]);
                    }
                }
                this.filter();
            },
            filter() {
                this.filteredResults = [];
                for (let i = 0; i < this.mutableResults.length; i++) {
                    this.filteredResults.push(this.mutableResults[i]);
                }
                this.paginate();
            },
            paginate() {
                let start_slicing_at = (this.pagination.currentPage - 1) * this.pagination.perPage;
                let stop_slicing_at = start_slicing_at + this.pagination.perPage;
                this.paginatedResults = this.filteredResults.slice(start_slicing_at, stop_slicing_at);
                if (this.pagination.currentPage > this.numPaginationPages) this.pagination.currentPage = 1;
            },
            search() {
                if (this.form.query !== "") {
                    this.loading = true;
                    let payload = new FormData();
                    payload.append("search_query", this.form.query);
                    this.axios.post(this.apiEndpoint, payload)
                        .then(function(response) {
                            console.log(this.tag+" response: ", response.data);
                            this.mutableResults = response.data.results;
                            this.filter();
                            this.loading = false;
                        }.bind(this))
                        .catch(function(error) {
                            console.warn(this.tag+" search request failed: ", error);
                            this.loading = false;
                        }.bind(this));
                }
            },
        },
        mounted() {
            this.initialize();
        }
    }
</script>

<style lang="scss">
    #search-results__wrapper {
        #search-controls {
            display: flex;
            margin: 0 0 50px 0;
            flex-direction: row;
            align-items: center;
            #search-controls__left {
                flex: 1;
                #search-bar {
                    width: 500px;
                    #search-bar__input {
                        width: 100%;
                    }
                    #search-bar__num-results {
                        font-size: .8em;
                        margin: 10px 0 0 0;
                        color: rgba(0, 0, 0, 0.5);
                    }
                }
            }
            #search-controls__right {

            }
        }
        #search-results {
            overflow: hidden;
            border-radius: 3px;
            background-color: #fff;
            .search-result__wrapper {
                border-bottom: 1px solid rgba(0, 0, 0, 0.1);
                &:last-child {
                    border-bottom: 0;
                }
                .search-result {
                    display: flex;
                    padding: 15px 20px;
                    position: relative;
                    flex-direction: row;
                    transition: all .3s;
                    text-decoration: none;
                    box-sizing: border-box;
                    color: rgba(0, 0, 0, 0.75);
                    background-color: hsl(0, 0%, 100%);
                    &:hover {
                        color: rgba(0, 0, 0, 1);
                        background-color: hsl(0, 0%, 95%);
                        .search-result__type-wrapper {
                            .search-result__type {
                                background-color: rgba(255, 255, 255, 1);
                            }
                        }
                    }
                    .search-result__score {
                        top: 15px;
                        right: 15px;
                        font-size: .8em;
                        position: absolute;
                        color: rgba(0, 0, 0, 0.25);
                    }
                    .search-result__type-wrapper {
                        flex: 0 0 150px;
                        margin: 0 15px 0 0;
                        .search-result__type {
                            width: 150px;
                            height: 50px;
                            display: flex;
                            border-radius: 3px;
                            align-items: center;
                            justify-content: center;
                            background-color: hsl(0, 0%, 95%);
                        }
                    }
                    .search-result__content {
                        flex: 1;
                        display: flex;
                        flex-direction: column;
                        justify-content: center;
                        .result-title {
                            font-size: 1.1em;
                            font-weight: 400;
                            margin: 0 0 3px 0;
                        }
                        .result-text {
                            font-size: .8em;
                            color: rgba(0, 0, 0, 0.75);
                        }
                    }
                }
            }
        }
        #no-results {
            padding: 25px;
            border-radius: 3px;
            text-align: center;
            box-sizing: border-box;
            background-color: #fff;
        }
        #loading {
            padding: 25px;
            border-radius: 3px;
            text-align: center;
            box-sizing: border-box;
            background-color: #fff;
            #loading-icon {
                font-size: 2em;
            }
        }
        #pagination {
            display: flex;
            margin: 30px 0 0 0;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }
    }
</style>