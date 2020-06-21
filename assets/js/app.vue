<template>
    <div id="edit">
        <h2>{{$t("hello_world")}}</h2>
        <spinner />
    </div>
</template>

<script>
    import moment from "moment";
    import Spinner from "./components/Spinner"

    const lang = document.documentElement.lang.substr(0, 2);
    moment.locale(lang);

    export default {
        data: function () {
            return {
                loaded: false
            }
        },
        components: {
            Spinner
        },
        methods: {}
    }

</script>
