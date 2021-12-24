<template>
  <section class="tables">
    <div class="page-header">
      <h3 class="page-title">Bootstrap Table</h3>
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="javascript:void(0);">Table</a>
          </li>
          <li class="breadcrumb-item active" aria-current="page">Все группы</li>
        </ol>
      </nav>
    </div>
    <div class="row">
      <div class="col-lg-12 grid-margin stretch-card">
        <div class="card">
          <div class="card-body">
            <h4 class="card-title"></h4>
            <b-table responsive :items="items" :fields="fields">
              <template #cell(actions)="row">
                <b-button
                  size="sm"
                  @click="setSubscribers(row.item, row.index, $event.target)"
                  class="mr-1"
                >
                  Задать подписчиков
                </b-button>
                <b-button
                  size="sm"
                  @click="deleteGroup(row.item, row.index, $event.target)"
                  class="mr-1"
                >
                  Удалить группу
                </b-button>
              </template>
              <!-- could also be a footer field slot instead -->
              <template slot="bottom-row">
                <td>Всего</td>
                <td></td>
                <!-- this is a computed prop that adds up all the expenses in the visible rows -->
                <td>{{ totalSubcribbers }}</td>
                <td></td>
              </template>
            </b-table>
            <!-- Info modal -->
            <b-modal
              ref="my-modal"
              :id="infoModal.id"
              :title="infoModal.title"
              @hide="resetInfoModal"
              @ok="handleOk"
            >
              <form ref="form" @submit.stop.prevent="handleSubmit">
                <b-form-group
                  label="Кол-во подписчиков"
                  label-for="name-input"
                  invalid-feedback="Name is required"
                >
                  <b-form-input
                    id="name-input"
                    v-model="countSubscriber"
                    required
                  ></b-form-input>
                </b-form-group>
              </form>
            </b-modal>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>
<script>
import axios from "axios";

export default {
  name: "group-all",
  components: {},
  data() {
    return {
      countSubscriber: 0,
      idGroup: "",
      items: Array,
      infoModal: {
        id: "info-modal",
        title: "",
        content: "",
      },
      fields: [
        {
          key: "name",
          label: "Название группы",
          sortable: true,
        },
        {
          key: "subscribers.count_subscriber",
          label: "Количество участников",
          sortable: true,
        },
        {
          key: "difference_point",
          label: "Прирост",
          sortable: true,
          // Variant applies to the whole column, including the header and footer
        },
        {
          key: "subscribers.updated_at",
          label: "Последнее обновление",
          sortable: true,
          // Variant applies to the whole column, including the header and footer
        },
        { key: "actions", label: "Actions" },
      ],
    };
  },
  methods: {
    handleOk(bvModalEvt) {
      // Prevent modal from closing
      bvModalEvt.preventDefault();
      // Trigger submit handler
      this.handleSubmit();
    },
    setSubscribers(item, index, button) {
      this.infoModal.title = item.name;
      this.idGroup = item.id;
      this.$root.$emit("bv::show::modal", this.infoModal.id, button);
    },
    deleteGroup(item, index, button) {
      this.idGroup = item.id;
      let self = this;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/group/delete", {
            id_group: self.idGroup,
          })
          .then((response) => {
            if (response.status) {
              console.log("Вызвали алерт");
              this.getGroups();
            } else {
              console.log("Не работает");
              console.log(response.status);
            }
          })
          .catch(function (error) {
            console.log(response);
            console.error(error);
          });
      });
    },
    getGroups() {
      let self = this;
      axios
        .get("/api/group")
        .then(function (response) {
          self.items = response.data.groups;
          console.log(response.data.groups);
        })
        .catch(function (error) {
          console.error(error);
        });
    },
    resetInfoModal() {
      this.infoModal.title = "";
      this.infoModal.content = "";
    },
    async handleSubmit() {
      let self = this;
      axios.get("/sanctum/csrf-cookie").then((response) => {
        axios
          .post("/api/group/set-subscriber", {
            count_subscriber: this.countSubscriber,
            id_group: self.idGroup,
          })
          .then((response) => {
            if (response.status) {
              console.log("Вызвали алерт");
              this.getGroups();
              this.$refs["my-modal"].hide();
            } else {
              console.log("Не работает");
              console.log(response.status);
            }
          })
          .catch(function (error) {
            console.log(response);
            console.error(error);
          });
      });
    },
  },
  mounted: function () {
    this.getGroups();
  },
  computed: {
    totalSubcribbers() {
      let count = 0;
      this.items.forEach((value, index) => {
        if (value.subscribers != null) {
          count += value.subscribers["count_subscriber"];
        }
      });
      return count;
    },
  },
};
</script>