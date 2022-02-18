<template>
  <div>
    <div v-if="!load" class="mb-2 mt-2">
      <button @click="load = true" class="btn btn-secondary">
        {{ question }}
      </button>
    </div>
    <slot v-else></slot>
  </div>

</template>

<script>
import {AtomSpinner} from 'epic-spinners'

export default {
  props: {
    question: {
      type: String,
      required: true
    }
  },
  data: function () {
    return {
      load: false
    }
  },
  components: {
    AtomSpinner
  }
}
</script>
