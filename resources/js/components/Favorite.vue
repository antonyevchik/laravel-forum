<template>
    <button type="submit" class="btn btn-outline-primary">
<!--       <span class="fas  fa-heart"></span>-->
        <i class="fas fa-heart"></i>
        <span v-text="favoritesCount"></span>
    </button>
</template>
<script>
    export default {
        data() {
            return {
                favoritesCount: 10
            }
        }


    }
</script>
