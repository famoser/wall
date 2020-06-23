<template>
    <div class="container-fluid h-100">
        <div class="row h-100">
            <div class="col-md-3 h-100 bg-secondary">
                <accounts class="mt-3" :users="users" :active-user="activeUser"></accounts>
            </div>
            <div class="col-md-6 h-100 bg-success">

            </div>
            <div class="col-md-3 h-100 bg-warning">

            </div>
        </div>
    </div>
</template>

<script>
    import moment from "moment";
    import axios from "axios";
    import Spinner from "./components/Spinner"
    import Accounts from "./components/Accounts"

    import Noty from 'noty';



    const lang = document.documentElement.lang.substr(0, 2);
    moment.locale(lang);

    export default {
        data: function () {
            return {
                loaded: false,
                activeUser: null,
                users: [{id: 12, name: "Cédric", karma: 200}, {id: 13, name: "Florian", karma: 12}, {
                    id: 14,
                    name: "Xenia",
                    me: false,
                    karma: 213
                }]
            }
        },
        components: {
            Spinner,
            Accounts
        },
        methods: {},
        mounted() {
            axios.interceptors.response.use(
                response => {
                    return response.data;
                },
                error => {
                    new Noty({
                        text: this.$t("messages.danger.unrecoverable") + " (" + error.response.data.message + ")",
                    }).show();

                    console.log("request failed");
                    console.log(error.response.data);
                    return Promise.reject(error);
                }
            );

            axios.get("/api/configuration").then((response) => {
                this.constructionSite = response.data.constructionSite;
                console.log(this.constructionSite);
                this.constructionSiteId = this.constructionSite.id;
            });
        }
    }
</script>

<style>
</style>
