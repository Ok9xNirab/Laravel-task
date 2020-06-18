<template>
  <div class="app-container">
    <div class="filter-container">
      <el-button
        class="filter-item"
        style="margin-left: 10px;"
        type="primary"
        icon="el-icon-edit"
        @click="dialogCreateForm"
      >
        ADD
      </el-button>
    </div>

    <el-table
      :key="tableKey"
      :data="list"
      border
      fit
      highlight-current-row
      style="width: 100%;"
    >
      <el-table-column
        :label="$t('table.id')"
        prop="id"
        sortable="custom"
        align="center"
        width="80"
      >
        <template slot-scope="scope">
          <span>{{ scope.row.id }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('table.name')" min-width="120px">
        <template slot-scope="scope">
          <span>{{ scope.row.name }}</span>
        </template>
      </el-table-column>

      <el-table-column
        :label="$t('table.actions')"
        align="center"
        width="230"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="scope">
          <el-button
            type="primary"
            size="mini"
            @click="dialogEditForm(scope.row.id)"
          >
            {{ $t('table.edit') }}
          </el-button>
          <el-button size="mini" type="danger" @click="deleteCat(scope.row.id)">
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog title="Edit Category" :visible.sync="editForm">
      <el-form ref="form" :model="form" label-width="120px">
        <el-form-item label="Name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="update">
            Update
          </el-button>
        </el-form-item>
      </el-form>
    </el-dialog>

    <el-dialog title="Create Category" :visible.sync="createForm">
      <el-form ref="form" :model="form" label-width="120px">
        <el-form-item label="Name">
          <el-input v-model="form.name" />
        </el-form-item>
        <el-form-item>
          <el-button type="primary" @click="create">
            Create
          </el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'Categories',
  data() {
    return {
      tableKey: 0,
      list: null,
      editForm: false,
      createForm: false,
      form: {
        name: '',
        id: '',
      },
    };
  },
  created() {
    this.getData();
  },
  methods: {
    dialogCreateForm() {
      this.createForm = true;
      this.form.name = '';
    },
    dialogEditForm(id) {
      this.editForm = true;
      const found = this.list.find(e => e.id === id);
      if (found) {
        this.form = found;
      }
    },
    create() {
      var root = this;
      axios.post('/api/create-category', this.form).then(response => {
        root.$message({
          message: 'Successfully Created',
          type: 'success',
        });
        root.createForm = false;
        root.getData();
      });
    },
    update(id) {
      var root = this;
      axios
        .post('/api/update-category/' + this.form.id, this.form)
        .then(response => {
          root.$message({
            message: 'Successfully Updated',
            type: 'success',
          });
          root.editForm = false;
          root.getData();
        });
    },
    deleteCat(id) {
      const check = confirm('Are You Sure to Delete ??');
      var root = this;
      if (check === true) {
        axios.get('/api/delete-category/' + id).then(response => {
          root.$message({
            message: 'Successfully Removed',
            type: 'success',
          });
          root.getData();
        });
      }
    },
    getData() {
      const root = this;
      axios.get('/api/all-categories').then(response => {
        root.list = response.data;
      });
    },
  },
};
</script>
