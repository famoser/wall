<template>
    <div>
        <div class="btn-group btn-group-toggle" data-toggle="buttons">
            <label class="btn btn-secondary" v-for="user in users" :id="user.id" v-model="selected"
                   :class="{'active': activeUser === user }">
                <input type="radio" name="options" id="option1" autocomplete="off" checked>
                {{ user.name }}
                <span class="badge badge-pill badge-info">{{user.karma}}</span>
            </label>
        </div>
        <button class="btn btn-outline-secondary">
            <font-awesome-icon :icon="['fal', 'sync']"></font-awesome-icon>
        </button>
    </div>
</template>

<script>
    export default {
        props: {
            users: {
                type: Array,
                required: true
            },
            activeUser: {
                type: Object
            }
        },
        data: function () {
            return {
                selected: null
            }
        }
    }
</script>

<style scoped>

</style>
