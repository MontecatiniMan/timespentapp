<template>
    <div>
    <table class="table">
        <th>Date</th>
        <th>Title</th>
        <th>Hours</th>
        <th>Actions</th>
        <tr>
            <td colspan="2" class="text-left"><button class="btn btn-outline-info" @click="addTask()">+</button></td>
            <td colspan="2" class="text-right">Total: {{ this.totalTasksHours() }} hours</td>
        </tr>
        <tr v-for="(task, key) in sortedTasks" :key="key" :class="{'undo': task.deleted_at !== null}">
            <td class="align-middle">
                <datepicker v-model="task.date" :disabled="task.deleted_at !== null" @closed="updateTask(task)"></datepicker>
            </td>
            <td class="align-middle">
                <textarea v-model="task.title" type="text" style="width: 250px; vertical-align: middle" :disabled="task.deleted_at !== null" @blur="updateTask(task)"/>
            </td>
            <td class="align-middle">
                <input v-model="task.hours" type="text" style="width: 40px" :disabled="task.deleted_at !== null" @blur="updateTask(task)" />
            </td>
            <td class="align-middle">
                <button :class="{'btn btn-success': task.deleted_at === null, 'btn btn-secondary': task.deleted_at !== null}" @click="deleteRestoreTask(task)" style="width: 100px">{{ task.deleted_at !== null ? 'Undo' : 'Finished' }}</button>
            </td>
        </tr>
    </table>
    </div>
</template>

<script>
    import Datepicker from "vuejs-datepicker";

    export default {
        props: ['token'],

        data() {
          return {
              'tasks': [],
              'apiUrl': 'http://dev.crud.ru/api/tasks',
          }
        },
        computed: {
            sortedTasks() {
                return this.tasks.sort((a, b) => b.id - a.id );
            }
        },
        methods: {
            totalTasksHours() {
                let total = 0;
                this.tasks.forEach(function (task) {
                    total += parseInt(task.hours);
                });

                return total;
            },
            deleteRestoreTask(task) {
                axios({
                    method: 'delete',
                    url: this.apiUrl + '/' + task.id,
                    headers: {
                        Authorization: 'Bearer ' + this.token,
                    }
                }).then(
                    response => {
                        task.deleted_at = task.deleted_at === null ? Date.now() : null;
                        console.log(response);
                    }
                );
            },
            addTask() {
                let newTask = {
                    'date': new Date().toISOString(),
                    'title': '',
                    'hours': 1,
                    'status': 1,
                    'deleted_at': null,
                };

                axios({
                    method: 'post',
                    url: this.apiUrl,
                    data: {
                        'task': newTask,
                    },
                    headers: {
                        Authorization: 'Bearer ' + this.token,
                    },
                }).then(
                    response => {
                        this.tasks.unshift(response.data);
                        console.log(response);
                    }
                );
            },
            updateTask(task) {console.log(task);
                axios({
                    method: 'patch',
                    url: this.apiUrl + '/' + task.id,
                    data: {
                        'taskData': JSON.stringify(task),
                    },
                    headers: {
                        Authorization: 'Bearer ' + this.token,
                    },
                }).then(
                    response => {
                        console.log(response);
                    }
                );
            }
        },
        mounted() {
            axios({
                method: 'get',
                url: this.apiUrl,
                headers: {
                    Authorization: 'Bearer ' + this.token,
                },
            }).then(
                response => {console.log(response);
                    this.tasks = response.data;
                }
            );
        },
        components: {
            Datepicker
        }
    }
</script>

<style lang="scss">
    .undo {
        td {
            text-decoration: line-through;
            color: #666;

            textarea, input {
                text-decoration: line-through;
                color: #666;
            }
        }
    }

    textarea, input {
        border: none;
    }
</style>
