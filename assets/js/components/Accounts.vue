<template>
    <div>
        <div class="btn-group btn-group-toggle">
            <label class="btn btn-secondary"
                   v-for="user in users"
                   :id="user.id"
                   :class="{'active': selected === user }">
                <input type="checkbox" autocomplete="off" :true-value="user" :false-value="null" v-model="selected">{{user.name}}
            </label>
        </div>

        <button class="btn btn-outline-secondary float-md-right">
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
            }
        },
        data: function () {
            return {
                selected: null
            }
        },
        watch: {
            selected: function (value) {
                if (value === null) {
                    return;
                }

                // authenticate

            }
        }
    }
</script>
