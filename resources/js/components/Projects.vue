<template>
    <div class="list-group">
        <a v-for="(project, key) in projects" href="#" class="list-group-item list-group-item-action" :key="key">
            {{ project }}
        </a>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                projects: [],
            }
        },
        mounted() {
            this.projects = [
                'Test project',
                'Another test project',
            ];
        }
    }
</script>
