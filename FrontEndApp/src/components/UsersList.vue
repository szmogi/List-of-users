<script setup lang="ts">
import { ref , onBeforeMount} from "vue";
import axios from "axios";

defineProps<{
  msg: string
}>()

const usersList = ref<any[]>([]);
const url = ref<String>('http://127.0.0.1:8000/');
const edit = ref<Boolean>(false);
const token = ref<any>('');

// filters value
const filter = ref({
  id: '',
  name: '',
  age: '',
  gender: ''
});

// User edit 
const editUser = ref({
  id: '',
  name: '',
  age: '',
  gender: ''
});

//user edit value set
const setEditUser = (user:any) => {
  editUser.value.id = user.id
  editUser.value.name = user.name
  editUser.value.age = user.age
  editUser.value.gender = user.gender
  edit.value = true
}

//user editing remove value
const removeEditUser = () => {
  editUser.value.id = ''
  editUser.value.name = ''
  editUser.value.age = ''
  editUser.value.gender = ''
}

//remove filter value
const removeFilter = () => {
  filter.value.id = ''
  filter.value.name = ''
  filter.value.age = ''
  filter.value.gender = ''
}

//get list users
const getUsersList = async () => {
   try {
        let result = await axios.get(url.value + 'list-users');
        usersList.value = result.data  
    } catch (error) {
        console.error('Error fetching user list:', error);
    }
}

//get token
const getToken = async () => {
   try {
        let result = await axios.get(url.value + 'token');
        token.value = result.data  
    } catch (error) {
        console.error('Error fetching user list:', error);
    }
}

// onBeforeMount hook
onBeforeMount(() => {
  getUsersList();
  getToken();
});

// search function
const searchUsers = async () => {
  console.log(filter.value)
  try {
    const response = await axios.get(url.value + 'search-users', {
      params: {
        id: filter.value.id,
        name: filter.value.name,
        age: filter.value.age,
        gender: filter.value.gender
      }
    });
    usersList.value = response.data; // Uloženie výsledkov do userList
  } catch (error) {
    console.error('Error fetching filtered users:', error);
  }
};


// destroy user
const deleteUser = async (id: number) => {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.value;
  try {
    await axios.delete(url.value +`delete-user/${id}`);
    // Aktualizácia zoznamu používateľov po odstránení
    usersList.value = usersList.value.filter(user => user.id !== id);
  } catch (error) {
    console.error('Error deleting user:', error);
  }
  usersList.value = usersList.value.filter(user => user.id !== id);
};


//update user
const updateUser = async () => {
  axios.defaults.headers.common['X-CSRF-TOKEN'] = token.value;
  try {
    await axios.put(url.value + `update-user/${editUser.value.id}`, editUser.value);     
  } catch (error) {
    console.error('Error updating user:', error);    
  }
  usersList.value = usersList.value.map(user =>
      user.id === editUser.value.id ? { ...user, ...editUser.value } : user
  );
};

</script>
<template>
  <div class="greetings w-100">
    <h1 class="green">{{ msg }}</h1>
   <div>
    <h3>Vyhľadávanie</h3>
     <div class="d-flex flex-row align-items-center">
       <div class="input-group input-group-sm mb-3">
        <span class="input-group-text" id="inputGroup-sizing-sm">Id</span>
        <input v-model="filter.id" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
      </div>
      <div class="input-group input-group-sm mb-3 ms-1">
        <span class="input-group-text" id="inputGroup-sizing-sm">Meno</span>
        <input v-model="filter.name" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
      </div>
      <div class="input-group input-group-sm mb-3 ms-1">
        <span class="input-group-text" id="inputGroup-sizing-sm">Vek</span>
        <input v-model="filter.age" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
      </div>
      <div class="input-group input-group-sm mb-3 ms-1">
        <span class="input-group-text" id="inputGroup-sizing-sm">Pohlavie</span>
        <input v-model="filter.gender" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
      </div>
      <button @click="searchUsers()" type="button" class="btn ms-1 mb-3 btn-primary">Hľadať</button>  
      <button @click="getUsersList(),removeFilter()" type="button" class="btn ms-1 mb-3 btn-primary">Reset</button>  
    </div>
   </div>
   <Transition>
   <div v-if="edit">
    <h3>Upraviť {{editUser.name}}</h3>
     <div class="d-flex flex-row align-items-center">    
      <div class="input-group input-group-sm mb-3">
        <span class="input-group-text" id="inputGroup-sizing-sm">Meno</span>
        <input v-model="editUser.name"  type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
      </div>
      <div class="input-group input-group-sm mb-3 ms-1">
        <span class="input-group-text" id="inputGroup-sizing-sm">Vek</span>
        <input v-model="editUser.age" type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
      </div>
      <div class="input-group input-group-sm mb-3 ms-1">
        <span class="input-group-text" id="inputGroup-sizing-sm">Pohlavie</span>
        <input v-model="editUser.gender"  type="text" class="form-control" aria-label="Sizing example input" aria-describedby="inputGroup-sizing-sm">
      </div>
      <button @click="updateUser()" type="button" class="btn ms-1 mb-3 btn-primary">Uložiť</button>  
      <button @click="removeEditUser() , edit = false"  type="button" class="btn ms-1 mb-3  btn-danger">X</button>
    </div>
   </div>
   </Transition>
   <table v-if='Object.keys(usersList).length > 0' class="table table-light">
      <thead>
        <tr>
          <th scope="col">ID</th>
          <th scope="col">Meno</th>
          <th scope="col">Vek</th>
          <th scope="col">Pohlavie</th>
          <th scope="col">Možnosti</th>
        </tr>
      </thead>
      <tbody>
        <tr  v-for="( user, index ) in usersList" :index="index">
          <th>{{user.id}}</th>
          <td>{{user.name}}</td>
          <td>{{user.age}}</td>
          <td>{{user.gender}}</td>
          <td>
            <button @click="setEditUser(user)" type="button" class="btn btn-primary">Upraviť</button>
            <button @click="deleteUser(user.id)"  type="button" class="btn mx-2 btn-danger">Zmazať</button>
          </td>
        </tr>       
      </tbody>
    </table>
    <div v-else>
        <h3 class="text-center px-3">Zoznam prázdny</h3>
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

.v-enter-from,
.v-leave-to {
  opacity: 0;
}
</style>
