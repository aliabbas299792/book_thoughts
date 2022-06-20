<script>
export default {
  data() {
    return {
      output_data: [],
      authors_data: [],
      books_data: [],
      query_data: {
        author: "",
        title: "",
        quote: "",
        comment: "",
        additional_info: "",
        chapter: "",
        chapter_section: "",
        at_or_after_date: new Date(0),
        before_date: new Date(0)
      },
      logged_in: false,
      show_login_modal: false,
      show_error_modal: false,
      login_data: {
        username: "",
        password: ""
      }
    }
  },
  watch: {
    query_data: {
      handler() {
        this.fetch_data()
      },
      deep: true
    }
  },
  methods: {
    async fetch_data() {
      const base_url = `https://erewhon.xyz/book_thoughts/api/get_thought.php`;
      const params = []

      if (this.query_data.author != "") {
        const author_id = this.authors_data.filter(item => item.author_name == this.query_data.author)[0].author_id
        params.push(`author_id=${author_id}`)
      }

      if (this.query_data.title != "") {
        const book_id = this.books_data.filter(item => item.book_name == this.query_data.title)[0].book_id
        params.push(`book_id=${book_id}`)
      }

      if (this.query_data.quote != "") {
        params.push(`quote=${this.query_data.quote}`)
      }

      if (this.query_data.comment != "") {
        params.push(`comment=${this.query_data.comment}`)
      }

      if (this.query_data.additional_info != "") {
        params.push(`additional_info=${this.query_data.additional_info}`)
      }

      if (this.query_data.chapter != "") {
        params.push(`chapter=${this.query_data.chapter}`)
      }

      if (this.query_data.chapter_section != "") {
        params.push(`chapter_section=${this.query_data.chapter_section}`)
      }

      const potential_at_or_after = Math.round(this.query_data.at_or_after_date.getDate() / 1000);
      if (potential_at_or_after != 0) {
        params.push(`at_or_after=${potential_at_or_after}`)
      }

      const potential_before = Math.round(this.query_data.before_date.getDate() / 1000);
      if (potential_before != 0) {
        params.push(`before=${potential_before}`)
      }

      const final_url = `${base_url}?${params.join("&")}`;

      const fetched = await fetch(final_url);
      const json_data = await fetched.json();
      this.output_data = json_data;
    },
    async get_authors() {
      const fetched = await fetch(`https://erewhon.xyz/book_thoughts/api/get_author.php`);
      const json_data = await fetched.json();
      this.authors_data = json_data;
    },
    async get_books() {
      const fetched = await fetch(`https://erewhon.xyz/book_thoughts/api/get_book.php`);
      const json_data = await fetched.json();
      this.books_data = json_data;
    },
    async is_logged_in() {
      const fetched = await fetch(`https://erewhon.xyz/book_thoughts/api/verify_user.php`);
      const text_data = await fetched.text();
      this.logged_in = text_data == "pass";
    },
    async try_login() {
      const fetched = await fetch(`https://erewhon.xyz/book_thoughts/api/login.php?username=${encodeURIComponent(this.login_data.username)}&password=${encodeURIComponent(this.login_data.password)}`);
      const text_data = await fetched.text();
      this.logged_in = text_data == "pass";

      if (this.logged_in) {
        this.show_login_modal = false;
      } else {
        this.show_error_modal = true;
        setTimeout(() => {
          this.show_error_modal = false
        }, 500)
      }
    },
    async logout() {
      await fetch(`https://erewhon.xyz/book_thoughts/api/logout.php`);
      this.logged_in = false;
    },
    async delete_thought(id) {
      await fetch(`https://erewhon.xyz/book_thoughts/api/delete_thought.php?id=${id}`);
      this.fetch_data();
    }
  },
  mounted() {
    this.fetch_data()
    this.get_authors()
    this.get_books()
    this.is_logged_in()
  }
}
</script>

<style>
@import url('https://fonts.googleapis.com/css2?family=Roboto+Serif:opsz,wght@8..144,300&display=swap');

.page {
  padding: 30px !important;
}

.filter_collapse {
  width: 100%;
  background: #fdfdfd;
  user-select: none;
}

.filters_container {
  padding: 20px 20px 1px 20px;
}

.filtered_data {
  padding: 20px 10px 0 10px;
}

.thought {
  margin: 0 0 20px 0;
  padding: 5px;
}

.comment_block {
  padding: 10px;
  margin: 10px;
  background: rgb(249, 249, 249);
  color: #333;
  padding-bottom: 5px;
  border-radius: 5px;
}

.quote_block {
  padding: 10px;
  background: rgb(249, 249, 249);
  color: #333;
  margin: 10px;
  padding-bottom: 5px;
  border-bottom: 5px solid #27ae5f;
  font-family: 'Roboto Serif', serif;
  border-radius: 5px;
}

.additional_info_block {
  padding: 5px;
  color: #fff;
  margin: 10px;
  border-radius: 5px;
}

.info_chip {
  margin: 5px;
}

.delete_chip {
  margin: 5px;
  float: right;
}
</style>

<template>
  <va-navbar color="rgb(234, 239, 244)">
    <template #left>
      <va-button @click="logout" v-if="logged_in">
        Logout
      </va-button>
      <va-button @click="show_login_modal = !show_login_modal" color="info" v-if="!logged_in">
        Login
      </va-button>
    </template>
    <template #center>
      <!-- the login stuff -->
      <va-navbar-item v-if="!logged_in">
        <va-modal v-model="show_login_modal" hide-default-actions overlay-opacity="0.2">
          <template #header>
            <h2 class="title">Login</h2>
            <br>
            <va-input class="mb-4" v-model="login_data.username" label="Username" />
            <va-input class="mb-4" type="password" v-model="login_data.password" label="Password" />
          </template>
          <template #footer>
            <va-button @click="try_login">
              Login
            </va-button>
          </template>
        </va-modal>
      </va-navbar-item>
    </template>
    <template #right>
      <va-navbar-item v-if="logged_in">
        <va-button outline class="mr-4">
          Add new author
        </va-button>

        <va-button outline class="mr-4">
          Add new book
        </va-button>

        <va-button outline class="mr-4">
          Add new thought
        </va-button>
      </va-navbar-item>
    </template>
  </va-navbar>

  <div class="page">
    <va-collapse solid class="filter_collapse" header="Filters" color="#f4f6f9" border-radius="0px">
      <div class="filters_container">
        <va-select class="mb-4" label="Search by book name" :options="books_data.map(item => item.book_name)"
          v-model="query_data.title" searchable />
        <va-select class="mb-4" label="Search by author name" :options="authors_data.map(item => item.author_name)"
          v-model="query_data.author" searchable />
        <va-input class="mb-4" v-model="query_data.quote" label="Search by quote" />
        <va-input class="mb-4" v-model="query_data.comment" label="Search by comments" />
        <va-input class="mb-4" v-model="query_data.additional_info" label="Search by additional info" />
        <va-input class="mb-4" v-model="query_data.chapter" label="Search by chapter" />
        <va-input class="mb-4" v-model="query_data.chapter_section" label="Search by chapter section" />
        <va-date-input class="mb-4" v-model="query_data.at_or_after_date" label="At or after date" />
        <va-date-input class="mb-4" v-model="query_data.before_date" label="Before date" />
      </div>
    </va-collapse>

    <div class="filtered_data">
      <va-card class="thought" v-for="item in output_data" :key="item" color="#3498db">
        <div class="quote_block" v-if="item.quote">{{ item.quote }}</div>
        <div class="comment_block" v-if="item.comment">{{ item.comment }}</div>
        <div class="additional_info_block" v-if="item.additional_info">{{ item.additional_info }}</div>
        <va-chip class="info_chip" v-if="item.author_name">Author: {{ item.author_name }}</va-chip>
        <va-chip class="info_chip" v-if="item.book_name">Title: {{ item.book_name }}</va-chip>
        <va-chip class="info_chip" v-if="item.chapter">Chapter: {{ item.chapter }}</va-chip>
        <va-chip class="info_chip" v-if="item.chapter_section">Chapter section: {{ item.chapter_section }}</va-chip>
        <va-chip class="info_chip" v-if="item.date_added">Added: {{ item.date_added }}</va-chip>
        <va-chip class="delete_chip" color="danger" v-if="logged_in" @click="delete_thought(item.thought_id)">Delete</va-chip>
      </va-card>
    </div>
    <va-modal v-model="show_error_modal" message="Failure" hide-default-actions title="Notice" />
  </div>
</template>
