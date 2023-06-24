<template>
    <table class="table text-white mb-0">
        <thead>
            <tr>
                <th scope="col">Todo</th>
                <th scope="col">Priority</th>
                <th scope="col">Status</th>
                <th scope="col">Assign User</th>
                <th scope="col">Last Update Time</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="todo in todoList" class="fw-normal">
                <th>
                    <div class="d-flex w-100 justify-content-between">
                        <h5 class="mb-1">{{ todo.title }}</h5>
                    </div>
                    <p class="mb-1">{{ todo.content }}</p>
                </th>
                <td class="align-middle">
                    <h6 class="mb-0"><span v-bind:class="getPriorityClass(todo.priority)">{{ todo.priority }} priority</span></h6>
                </td>
                <td class="align-middle">
                    <span>{{ todo.status }}</span>
                </td>
                <td class="align-middle">
                    <span>{{ getUser(todo.assign_user_id) }}</span>
                </td>
                <td class="align-middle">
                    <span>{{ todo.updated_at }}</span>
                </td>
                <td class="align-middle">
                    <a v-if="displayTool(todo)" href="#!" data-toggle="tooltip" data-placement="bottom" title="Edit" @click="chileToggleEditModal(todo)"><i
                            class="bi bi-pencil-square"></i></a>
                    <a v-if="displayTool(todo)" href="#!" data-toggle="tooltip" data-placement="bottom" title="Remove" @click="chileToggleDeleteModal(todo)" style="color:#ff9800;"><i
                            class="bi bi-trash-fill"></i></a>
                    <a v-if="displayLock(todo)" href="#!" data-toggle="tooltip" data-placement="bottom" title="Lock" @click="handleLock(todo)" style="color:#ffc400;"><i
                            class="bi bi-lock-fill"></i></a>
                    <a v-if="displayUnLock(todo)" href="#!" data-toggle="tooltip" data-placement="bottom" title="Unlock" @click="handleUnLock(todo)" style="color:#00ff99;"><i
                            class="bi bi-unlock-fill"></i></a>
                    <a v-if="displayLockByOther(todo)" href="#!" data-toggle="tooltip" data-placement="bottom" title="Already locked by other user" style="color:rgb(255, 81, 0);"><i
                            class="bi bi-lock-fill"></i></a>
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script>
export default {
    props: {
        setTodoData: Function,
        toggleEditModal: Function,
        toggleDeleteModal: Function,
        todos: Array,
        getTodos: Function,
        users: Array,
        showError: Function,
    },

    data() {
        return {
            todoList: this.todos,
            addActive: false,
            addShow: false,
            editActive: false,
            editShow: false,
            todoData: {
                id: null,
                title: '',
                content: '',
                priority: '',
                assign_user_id: ''
            },
            operatorId: null,
            userList: this.users.reduce((obj, item) => (obj[item.id] = item.name, obj) ,{})
        }
    },

    created() {
        this.getOperator()
    },

    watch: {
        todos(newData) {
            this.todoList = newData
        },
        users(newData) {
            this.userList = newData.reduce((obj, item) => (obj[item.id] = item.name, obj) ,{})
        }
    },

    methods: {
        getOperator(){
            fetch('/api/user')
                .then(response => response.json())
                .then(response => {
                    this.operatorId = response.data.id;
                })
                .catch(err => console.log(err));
        },
        displayTool(todo){
            if (!todo.lock_operator_id) {
                return true
            }

            if (todo.lock_operator_id == this.operatorId) {
                return true
            }

            return false
        },
        displayLock(todo){
            if (!todo.lock_operator_id) {
                return true
            }

            return false
        },
        displayUnLock(todo){
            if (todo.lock_operator_id && todo.lock_operator_id == this.operatorId) {
                return true
            }

            return false
        },
        displayLockByOther(todo){
            if (todo.lock_operator_id && todo.lock_operator_id != this.operatorId) {
                return true
            }

            return false
        },
        getPriorityClass(todoStatus) {
            switch (todoStatus) {
                case "High":
                    return {
                        "badge bg-danger": true
                    }
                case "Middle":
                    return {
                        "badge bg-warning": true
                    } 
                case "Low":
                    return {
                        "badge bg-success": true
                    }
            }
        },
        chileToggleEditModal(todo) {
            this.setTodoData(todo.id, todo.title, todo.content, todo.priority, todo.status, todo.assign_user_id)
            this.toggleEditModal()
        },
        chileToggleDeleteModal(todo) {
            this.setTodoData(todo.id, todo.title, todo.content, todo.priority, todo.status, todo.assign_user_id)
            this.toggleDeleteModal()
        },
        handleLock(todo) {
            const headers = {
                "Content-Type": "application/json",
            }
            fetch(`/api/todo/${ todo.id }/lock`, {
                method: 'PUT',
                headers: headers,
                body: {},
            })
                .then(response => {
                    if (response.ok) {
                        this.getTodos()
                    } else {
                        throw response
                    }
                })
                .catch(error => {
                    if (typeof error.json === "function") {
                        error.json().then(jsonError => {
                            console.log(jsonError);
                            this.showError(jsonError.message);
                        }).catch(genericError => {
                            this.showError("Something got wrong");
                        });
                    } else {
                        console.log(error);
                        this.showError("Something got wrong");
                    }
                });
        },
        handleUnLock(todo) {
            const headers = {
                "Content-Type": "application/json",
            }
            fetch(`/api/todo/${ todo.id }/unlock`, {
                method: 'PUT',
                headers: headers,
                body: {},
            })
                .then(response => {
                    if (response.ok) {
                        this.getTodos()
                    } else {
                        throw response
                    }
                })
                .catch(error => {
                    if (typeof error.json === "function") {
                        error.json().then(jsonError => {
                            console.log(jsonError);
                            this.showError(jsonError.message);
                        }).catch(genericError => {
                            this.showError("Something got wrong");
                        });
                    } else {
                        console.log(error);
                        this.showError("Something got wrong");
                    }
                });
        },
        getUser(id) {
            if (this.userList) {
                return this.userList[id]
            }

            return ''
        }
    }
};
</script>
