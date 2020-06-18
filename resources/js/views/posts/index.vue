<template>
  <div class="app-container">
    <div class="filter-container">
      <router-link to="/create-post">
        <el-button
          class="filter-item"
          style="margin-left: 10px;"
          type="primary"
          icon="el-icon-edit"
        >
          ADD
        </el-button>
      </router-link>
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

      <el-table-column :label="$t('table.title')" min-width="120px">
        <template slot-scope="scope">
          <span>{{ scope.row.title }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('Slugs')" width="120px" align="center">
        <template slot-scope="scope">
          <span>{{ scope.row.slug }}</span>
        </template>
      </el-table-column>

      <el-table-column :label="$t('Categories')" width="100px">
        <template slot-scope="scope">
          <span v-text="getCatName(scope.row.categories)"></span>
        </template>
      </el-table-column>

      <el-table-column
        :label="$t('table.actions')"
        align="center"
        width="230"
        class-name="small-padding fixed-width"
      >
        <template slot-scope="scope">
          <router-link :to="'/edit-post/' + scope.row.id">
            <el-button type="primary" size="mini">
              {{ $t('table.edit') }}
            </el-button>
          </router-link>
          <el-button
            size="mini"
            type="success"
            @click="dialogViewForm(scope.row.id)"
          >
            {{ $t('View') }}
          </el-button>
          <el-button
            size="mini"
            type="danger"
            @click="deletePost(scope.row.id)"
          >
            {{ $t('table.delete') }}
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <pagination
      v-show="meta.total > 0"
      :total="meta.total"
      :page.sync="meta.current_page"
      :limit.sync="meta.per_page"
      @pagination="getPosts(meta.next_page_url)"
    />

    <el-dialog title="Full Posts Preview" :visible.sync="viewForm">
      <strong>Post Title : </strong>
      <p>
        {{ singleData.title }}
      </p>
      <strong>Post Slug : </strong>
      <p>
        {{ singleData.slug }}
      </p>
      <strong>Content : </strong>
      <p class="lead" v-html="singleData.body"></p>
      <strong>Category : </strong>
      <p v-text="getCatName(singleData.categories)"></p>
    </el-dialog>
  </div>
</template>

<script>
import Pagination from '@/components/Pagination';
import axios from 'axios';
export default {
  name: 'Posts',
  components: { Pagination },
  data() {
    return {
      tableKey: 0,
      list: null,
      url: '',
      meta: {
        first_page_url: null,
        last_page_url: null,
        from_data: null,
        last_page: null,
        next_page_url: null,
        prev_page_url: null,
        path: null,
        per_page: null,
        total: 0,
        to: 0,
      },
      viewForm: false,
      singleData: {},
      categories: [],
    };
  },
  created() {
    this.getPosts();
    this.getCat();
  },
  methods: {
    dialogViewForm(id) {
      this.viewForm = true;
      const found = this.list.find(e => e.id === id);
      if (found) {
        this.singleData = found;
      }
    },
    getCatName(id) {
      var result = '';
      this.categories.forEach(category => {
        if (category.id === parseInt(id)) {
          result = category;
        }
      });
      return result.name;
    },
    getPosts(page = null) {
      const root = this;
      if (page === null) {
        this.url = '/api/all-posts';
      } else {
        this.url = page;
      }
      axios.get(this.url).then(response => {
        root.meta = response.data;
        root.list = response.data.data;
      });
    },
    getCat() {
      const root = this;
      axios.get('/api/all-categories').then(response => {
        root.categories = response.data;
      });
    },
    deletePost(id) {
      const check = confirm('Are You Sure to Delete ??');
      var root = this;
      if (check === true) {
        axios.get('/api/delete-post/' + id).then(response => {
          root.$message({
            message: 'Successfully Removed',
            type: 'success',
          });
          root.getPosts();
        });
      }
    },
  },
};
</script>
