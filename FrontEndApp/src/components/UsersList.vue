<script setup lang="ts">
import { ref, onBeforeMount , computed } from "vue";
import axios from "axios";

// props
defineProps<{
  msg: string;
}>();

// variables
const usersList = ref<any[]>([]);
const url = ref<String>("/");
const message = ref<String>("");
const edit = ref<Boolean>(false);
const filtered = ref<Boolean>(false);
const token = ref<any>("");
const currentPage = ref<any>(1);
const pageSize = ref<any>(5);

// filters value
const filter = ref({
  id: "",
  name: "",
  age: "",
  gender: "",
});

// User edit value
const editUser = ref({
  id: "",
  name: "",
  age: "",
  gender: "",
});

//user edit value set
const setEditUser = (user: any) => {
  editUser.value.id = user.id;
  editUser.value.name = user.name;
  editUser.value.age = user.age;
  editUser.value.gender = user.gender;
  edit.value = true;
};

//user editing remove value
const removeEditUser = () => {
  editUser.value.id = "";
  editUser.value.name = "";
  editUser.value.age = "";
  editUser.value.gender = "";
};

//remove filter value
const removeFilter = () => {
  filter.value.id = "";
  filter.value.name = "";
  filter.value.age = "";
  filter.value.gender = "";
  filtered.value = false;
  getUsersList();
};

//get list users
const getUsersList = async () => {
  try {
    let result = await axios.get(url.value + "list-users");
    usersList.value = result.data;
  } catch (error) {
    console.error("Error fetching user list:", error);
  }
};

//get token
const getToken = async () => {
  try {
    let result = await axios.get(url.value + "token");
    token.value = result.data;
  } catch (error) {
    console.error("Error fetching token:", error);
  }
};

// onBeforeMount hook
onBeforeMount(() => {
  getUsersList();
  getToken();
});

// search function
const searchUsers = async () => {
  filtered.value = true;
  try {
    const response = await axios.get(url.value + "search-users", {
      params: {
        id: filter.value.id,
        name: filter.value.name,
        age: filter.value.age,
        gender: filter.value.gender,
      },
    });
    usersList.value = response.data; // Uloženie výsledkov do userList
    setMessage("Nájdené " + Object.keys(usersList.value).length);
  } catch (error) {
    console.error("Error fetching filtered users:", error);
  }
};

// destroy user
const deleteUser = async (id: number) => {
  try {
    const response = await axios.delete(url.value + `delete-user/${id}`);
    usersList.value = response.data;
    setMessage("Užívateľ ID " + id + " bol odstránení.");
  } catch (error) {
    console.error("Error deleting user:", error);
  }
};

//update user
const updateUser = async () => {
  try {
    const response = await axios.put(
      url.value + `update-user/${editUser.value.id}`,
      editUser.value
    );
    usersList.value = response.data;
    setMessage("Užívateľ bol upravený.");
  } catch (error) {
    console.error("Error updating user:", error);
  }
};

// set message
const setMessage = async (msg: String, time: number = 1500) => {
  message.value = msg;
  setTimeout(() => {
    message.value = "";
  }, time);
};

// pager
const currentPageUsers = computed(() => {
  const startIndex = (currentPage.value - 1) * pageSize.value;
  const endIndex = startIndex + pageSize.value;
  usersList.value = proxyObjectToArray(usersList.value);
  return usersList.value.slice(startIndex, endIndex);
});

// proxy object to array
function proxyObjectToArray(proxyObject: any) {
  const array = [];
  for (const key in proxyObject) {
    if (Object.prototype.hasOwnProperty.call(proxyObject, key)) {
      array.push(proxyObject[key]);
    }
  }
  return array;
}

//reset pager
const resetPager = () => {
  currentPage.value = 1;
};

//show next users
const showNextUsers = () => {
  currentPage.value++;
};

//show previous users
const showPreviousUsers = () => {
  if (currentPage.value > 1) {
    currentPage.value--;
  }
};
</script>
<template>
  <div class="greetings w-100">
    <div class="d-flex flex-row justify-content-between">
      <h1 class="green">{{ msg }}</h1>
      <transition name="fade">
        <div
          v-if="message.length > 0"
          class="toast align-items-center show text-bg-success border-0"
          role="alert"
          aria-live="assertive"
          aria-atomic="true"
        >
          <div class="d-flex">
            <div class="toast-body">
              {{ message }}
            </div>
            <button
              type="button"
              class="btn-close btn-close-white me-2 m-auto"
              data-bs-dismiss="toast"
              aria-label="Close"
            ></button>
          </div>
        </div>
      </transition>
    </div>
    <div>
      <h3>Vyhľadávanie</h3>
      <div class="d-flex flex-row align-items-center">
        <div class="input-group input-group-sm mb-3">
          <span class="input-group-text" id="inputGroup-sizing-sm">Id</span>
          <input
            v-model="filter.id"
            type="text"
            class="form-control"
            aria-label="Sizing example input"
            aria-describedby="inputGroup-sizing-sm"
          />
        </div>
        <div class="input-group input-group-sm mb-3 ms-1">
          <span class="input-group-text" id="inputGroup-sizing-sm">Meno</span>
          <input
            v-model="filter.name"
            type="text"
            class="form-control"
            aria-label="Sizing example input"
            aria-describedby="inputGroup-sizing-sm"
          />
        </div>
        <div class="input-group input-group-sm mb-3 ms-1">
          <span class="input-group-text" id="inputGroup-sizing-sm">Vek</span>
          <input
            v-model="filter.age"
            type="text"
            class="form-control"
            aria-label="Sizing example input"
            aria-describedby="inputGroup-sizing-sm"
          />
        </div>
        <div class="input-group input-group-sm mb-3 ms-1">
          <span class="input-group-text" id="inputGroup-sizing-sm">Pohlavie</span>
          <input
            v-model="filter.gender"
            type="text"
            class="form-control"
            aria-label="Sizing example input"
            aria-describedby="inputGroup-sizing-sm"
          />
        </div>
        <button @click="searchUsers()" type="button" class="btn ms-1 mb-3 btn-primary">
          Hľadať
        </button>
        <button
          v-if="filtered"
          @click="getUsersList(), removeFilter()"
          type="button"
          class="btn ms-1 mb-3 btn-primary"
        >
          Reset
        </button>
      </div>
    </div>
    <transition name="fade">
      <div v-if="edit">
        <h3>Upraviť {{ editUser.name }}</h3>
        <div class="d-flex flex-row align-items-center">
          <div class="input-group input-group-sm mb-3">
            <span class="input-group-text" id="inputGroup-sizing-sm">Meno</span>
            <input
              v-model="editUser.name"
              type="text"
              class="form-control"
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
            />
          </div>
          <div class="input-group input-group-sm mb-3 ms-1">
            <span class="input-group-text" id="inputGroup-sizing-sm">Vek</span>
            <input
              v-model="editUser.age"
              type="text"
              class="form-control"
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
            />
          </div>
          <div class="input-group input-group-sm mb-3 ms-1">
            <span class="input-group-text" id="inputGroup-sizing-sm">Pohlavie</span>
            <input
              v-model="editUser.gender"
              type="text"
              class="form-control"
              aria-label="Sizing example input"
              aria-describedby="inputGroup-sizing-sm"
            />
          </div>
          <button @click="updateUser()" type="button" class="btn ms-1 mb-3 btn-primary">
            Uložiť
          </button>
          <button
            @click="removeEditUser(), (edit = false)"
            type="button"
            class="btn ms-1 mb-3 btn-danger"
          >
            X
          </button>
        </div>
      </div>
    </transition>
    <table v-if="Object.keys(usersList).length > 0" class="table table-light">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Meno</th>
          <th scope="col">Vek</th>
          <th scope="col">Pohlavie</th>
          <th scope="col">Možnosti</th>
        </tr>
      </thead>
      <transition-group name="table" tag="tbody">
        <tr v-for="(user, index) in currentPageUsers" :index="index">
          <th>{{ user.id }}</th>
          <td>{{ user.name }}</td>
          <td>{{ user.age }}</td>
          <td>{{ user.gender }}</td>
          <td>
            <button @click="setEditUser(user)" type="button" class="btn btn-primary">
              Upraviť
            </button>
            <button
              @click="deleteUser(user.id)"
              type="button"
              class="btn mx-2 btn-danger"
            >
              Zmazať
            </button>
          </td>
        </tr>
      </transition-group>
    </table>
    <div v-else>
      <h3 class="text-center py-3">Zoznam prázdny</h3>
    </div>
    <div v-if="Object.keys(usersList).length > 0" class="d-flex justify-content-center">
      <button
        type="button"
        class="btn btn-link"
        v-if="currentPage > 1"
        @click="showPreviousUsers"
      >
        Späť
      </button>
      <button
        type="button"
        class="btn btn-link"
        v-if="usersList.length > pageSize * currentPage"
        @click="showNextUsers"
      >
        Ďalej
      </button>
    </div>
  </div>
</template>

<style scoped>
h1 {
  font-weight: 500;
  font-size: 2.6rem;
  top: -10px;
}

h3 {
  font-size: 1.2rem;
}

.greetings h1,
.greetings h3 {
  text-align: center;
}

@media (min-width: 1024px) {
  .greetings h1,
  .greetings h3 {
    text-align: left;
  }
}

/* we will explain what these classes do next! */
.v-enter-active,
.v-leave-active {
  transition: opacity 0.5s ease;
}

/* Transition classes for adding/removing rows */
.table-enter-active,
.table-leave-active {
  transition: all 0.5s ease;
}

.table-enter-from,
.table-leave-to {
  opacity: 0;
  transform: translateY(20px);
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.5s;
}
.fade-enter, .fade-leave-to /* .fade-leave-active in <2.1.8 */ {
  opacity: 0;
}
</style>
