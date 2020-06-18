<template>
  <div class="app-container">
    <el-form ref="form" :model="form" label-width="120px">
      <el-form-item label="Title">
        <el-input v-model="form.title" @change="sluggg" />
      </el-form-item>
      <el-form-item label="Slug">
        <el-input v-model="form.slug" />
      </el-form-item>
      <el-form-item label="Categories">
        <el-select
          v-model="form.categories"
          placeholder="please select your Category"
        >
          <el-option
            v-for="category in categories"
            :key="category.id"
            :label="category.name"
            :value="category.id"
          />
        </el-select>
      </el-form-item>
      <el-form-item label="Content">
        <tinymce v-model="form.body" :height="300" />
      </el-form-item>
      <el-form-item>
        <el-button type="primary" @click="update">
          Update
        </el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import Tinymce from '@/components/Tinymce';
import axios from 'axios';
export default {
  name: 'EditPost',
  components: { Tinymce },
  data() {
    return {
      list: {},
      categories: [],
      form: {
        title: '',
        slug: '',
        categories: '',
        body: '',
      },
    };
  },
  created() {
    this.getCat();
    this.getPosts();
  },
  methods: {
    update() {
      if (
        this.form.title === '' ||
        this.form.categories === '' ||
        this.form.slug === '' ||
        this.form.body === ''
      ) {
        this.$message({
          message: 'All Fields Are Required !!',
          type: 'error',
        });
        return;
      }
      var root = this;
      axios
        .post('/api/update-post/' + this.$route.params.id, this.form)
        .then(response => {
          root.$message({
            message: 'Successfully updated',
            type: 'success',
          });
        })
        .catch(error => {
          root.$message({
            message: error.response.data.errors.slug[0],
            type: 'error',
          });
        });
    },
    sluggg() {
      this.form.slug = this.slugify(this.form.title);
    },
    slugify(text, ampersand = 'and') {
      const a = 'àáäâèéëêìíïîòóöôùúüûñçßÿỳýœæŕśńṕẃǵǹḿǘẍźḧ';
      const b = 'aaaaeeeeiiiioooouuuuncsyyyoarsnpwgnmuxzh';
      const p = new RegExp(a.split('').join('|'), 'g');
      return text
        .toString()
        .toLowerCase()
        .replace(/[\s_]+/g, '-')
        .replace(p, c => b.charAt(a.indexOf(c)))
        .replace(/&/g, `-${ampersand}-`)
        .replace(/[^\w-]+/g, '')
        .replace(/--+/g, '-')
        .replace(/^-+|-+$/g, '');
    },
    getPosts() {
      const root = this;
      axios.get('/api/get-post/' + this.$route.params.id).then(response => {
        root.list = response.data;
        root.form = {
          title: response.data.title,
          slug: response.data.slug,
          body: response.data.body,
          categories: root.getCatName(response.data.categories),
        };
      });
    },
    getCat() {
      const root = this;
      axios.get('/api/all-categories').then(response => {
        root.categories = response.data;
      });
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
  },
};
</script>

<style></style>
