<template>
    <div>
        <atom-spinner v-if="spin"
                :animation-duration="1000"
                :size="60"
                color="#ff1d5e"
        />
        <slot v-else></slot>
    </div>

</template>

<script>
    import { AtomSpinner } from 'epic-spinners'

    export default {
        props: {
            spin: {
                type: Boolean,
                required: true
            }
        },
        components: {
            AtomSpinner
        }
    }
</script>
