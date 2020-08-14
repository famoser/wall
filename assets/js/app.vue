<template>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-xl-3 h-lg-100 d-flex flex-column bg-light col-lg-6">
                <div class="card mt-2">
                    <div class="card-body">
                        <spinner :spin="users === null">
                            <accounts :users="users"
                                      :edit-mode="editMode"
                                      @toggle-edit-mode="editMode = !editMode"
                                      @select-user="selectUser"
                                      @post-user="postUser"
                                      @patch-user="patch"
                                      @delete-user="deleteUser"
                            ></accounts>
                        </spinner>
                    </div>
                </div>
                <div class="card mt-2 mb-2 flex-lg-grow-1 overflow-auto">
                    <div class="card-body">
                        <spinner :spin="products === null">
                            <products :products="products"
                                      :edit-mode="editMode"
                                      :authorized="selectedUser !== null"
                                      @reward="reward"
                                      @post-product="postProduct"
                                      @patch-product="patch"
                                      @delete-product="deleteProduct">
                            </products>
                        </spinner>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 h-lg-100 d-flex flex-column bg-light col-lg-6">
                <div class="card mt-2 mh-lg-25 overflow-auto">
                    <div class="card-body">
                        <spinner :spin="questions === null || answers === null || users === null">
                            <questions :questions="questions"
                                       :answers="answers"
                                       :users="users"
                                       :edit-mode="editMode"
                                       :authorized-user="selectedUser"
                                       @reward="reward"
                                       @post-question="postQuestion"
                                       @patch-question="patch"
                                       @delete-question="deleteQuestion"
                                       @post-answer="postAnswer"
                                       @patch-answer="patch"
                                       @delete-answer="deleteAnswer">
                            </questions>
                        </spinner>
                    </div>
                </div>
                <div class="card mt-2 mh-lg-25 overflow-auto">
                    <div class="card-body">
                        <spinner :spin="events === null">
                            <events :events="events"
                                    :edit-mode="editMode"
                                    @reward="reward"
                                    @post-event="postEvent"
                                    @patch-event="patch"
                                    @delete-event="deleteEvent">
                            </events>
                        </spinner>
                    </div>
                </div>
                <div class="card mt-2 mb-2 flex-lg-grow-1 overflow-auto">
                    <div class="card-body">
                        <spinner :spin="tasks === null">
                            <tasks :tasks="tasks"
                                   :edit-mode="editMode"
                                   :authorized="selectedUser !== null"
                                   @reward="reward"
                                   @post-task="postTask"
                                   @patch-task="patch"
                                   @delete-task="deleteTask">
                            </tasks>
                        </spinner>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 mh-lg-100 bg-light col-lg-12 overflow-auto">
                <spinner :spin="embeds === null">
                    <embeds :embeds="embeds"
                           :edit-mode="editMode"
                           @reward="reward"
                           @post-embed="postEmbed"
                           @patch-embed="patch"
                           @delete-embed="deleteEmbed">
                    </embeds>
                </spinner>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import axios from "axios";
    import Accounts from "./components/Accounts"
    import Events from "./components/Events"
    import Products from "./components/Products"
    import Questions from "./components/Questions"
    import Spinner from "./components/Spinner"

    import Noty from 'noty';
    import Tasks from "./components/Tasks";
    import Embeds from "./components/Embeds";

    const lang = document.documentElement.lang.substr(0, 2);
    moment.locale(lang);

    export default {
        data: function () {
            return {
                products: null,
                users: null,
                events: null,
                questions: null,
                answers: null,
                tasks: null,
                embeds: null,
                editMode: false,
                selectedUser: null,
                secret: null
            }
        },
        components: {
            Embeds,
            Tasks,
            Accounts,
            Events,
            Products,
            Questions,
            Spinner
        },
        methods: {
            reward: function (reward) {
                let user = this.selectedUser;
                axios.patch(user["@id"] + "/reward", {
                    "karma": reward
                }).then((response) => {
                    Object.assign(user, response.data);
                });
            },
            selectUser: function (user, secret) {
                this.selectedUser = user;
                this.secret = secret
            },
            postUser: function (user) {
                this.post("/api/users", user, this.users);
            },
            deleteUser: function (entity) {
                this.delete(entity, this.users);
            },
            postProduct: function (product) {
                this.post("/api/products", product, this.products);
            },
            deleteProduct: function (entity) {
                this.delete(entity, this.products);
            },
            postEvent: function (event) {
                this.post("/api/events", event, this.events);
            },
            deleteEvent: function (entity) {
                this.delete(entity, this.events);
            },
            postTask: function (task) {
                this.post("/api/tasks", task, this.tasks);
            },
            deleteTask: function (entity) {
                this.delete(entity, this.tasks);
            },
            postEmbed: function (event) {
                this.post("/api/embeds", event, this.embeds);
            },
            deleteEmbed: function (entity) {
                this.delete(entity, this.embeds);
            },
            postSetting: function (key, setting) {
                setting.key = key;
                this.post("/api/settings", setting, this.settings);
            },
            post: function (url, payload, list) {
                axios.post(url, payload).then((response) => {
                    const newObj = Object.assign({}, response.data);
                    list.push(newObj);
                });
            },
            patch: function (entity, payload) {
                axios.patch(entity['@id'], payload).then((response) => {
                  console.log(payload);
                  console.log(response.data);
                    Object.assign(entity, response.data);
                });
            },
            delete: function (entity, list) {
                axios.delete(entity['@id']).then(() => {
                    let index = list.indexOf(entity);
                    this.$delete(list, index)
                });
            },
            postQuestion: function (question) {
                this.post("/api/questions", question, this.questions);
            },
            deleteQuestion: function (question) {
                this.delete(question, this.questions);
            },
            postAnswer: function (answer) {
                this.post("/api/answers", answer, this.answers);
            },
            deleteAnswer: function (answer) {
                this.delete(answer, this.answers);
            }
        },
        mounted() {
            axios.interceptors.response.use(
                response => {
                    return response;
                },
                error => {
                    if (error.response) {
                        new Noty({
                            text: this.$t("messages.danger.request_failed") + " (" + error.response.data.status + ": " + error.response.data.detail + ")",
                            theme: 'bootstrap-v4',
                            type: 'error'
                        }).show();
                    } else {
                        new Noty({
                            text: this.$t("messages.danger.request_failed") + " (" + error + ")",
                            theme: 'bootstrap-v4',
                            type: 'error'
                        }).show();
                    }

                    console.log("request failed");
                    console.log(error.response.data);
                    return Promise.reject(error);
                }
            );

            axios.get("/api/users").then((response) => {
                this.users = response.data["hydra:member"];
            });

            axios.get("/api/products").then((response) => {
                this.products = response.data["hydra:member"];
            });

            axios.get("/api/questions").then((response) => {
                this.questions = response.data["hydra:member"];
            });

            axios.get("/api/answers").then((response) => {
                this.answers = response.data["hydra:member"];
            });

            const today = moment().startOf("day").format("YYYY-MM-DD");
            axios.get("/api/events?startAt[after]=" + today).then((response) => {
                this.events = response.data["hydra:member"];
            });

            axios.get("/api/tasks").then((response) => {
                this.tasks = response.data["hydra:member"];
            });

            axios.get("/api/embeds").then((response) => {
                this.embeds = response.data["hydra:member"];
            });
        }
    }
</script>

<style>
</style>
