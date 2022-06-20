<script>
export default {
  data() {
    return {
      current_view: "thoughts",
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
      },

      add_author_data: {
        name: "",
        link: ""
      },
      add_author_modal: false,

      add_book_data: {
        name: "",
        author: "",
        link: ""
      },
      add_book_modal: false,

      add_thought_data: {
        book: "",
        quote: "",
        comment: "",
        additional_info: "",
        chapter: "",
        chapter_section: ""
      },
      add_thought_modal: false
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
    },
    async delete_book(id) {
      await fetch(`https://erewhon.xyz/book_thoughts/api/delete_book.php?id=${id}`);
      this.get_books();
      this.fetch_data();
    },
    async delete_author(id) {
      await fetch(`https://erewhon.xyz/book_thoughts/api/delete_author.php?id=${id}`);
      this.get_authors();
      this.get_books();
      this.fetch_data();
    },
    async add_author() {
      const fetched = await fetch(`https://erewhon.xyz/book_thoughts/api/add_author.php?name=${encodeURIComponent(this.add_author_data.name)}&link=${encodeURIComponent(this.add_author_data.link)}`);
      const text_data = await fetched.text();
      this.add_author_modal = false;
      if (text_data == "fail") {
        this.show_error_modal = true;
        setTimeout(() => {
          this.show_error_modal = false
        }, 500)
      } else {
        this.get_authors();
      }
    },
    async add_book() {
      let author_param = "";
      let link_param = "";

      if (this.add_book_data.author != "") {
        author_param = `&author_id=${this.authors_data.filter(item => item.author_name == this.add_book_data.author)[0].author_id}`;
      }

      if (this.add_book_data.link != "") {
        link_param = `&link=${encodeURIComponent(this.add_author_data.link)}`;
      }

      const fetched =
        await fetch(
          `https://erewhon.xyz/book_thoughts/api/add_book.php?
            name=${encodeURIComponent(this.add_author_data.name)}
            ${link_param}
            ${author_param}`
        );

      const text_data = await fetched.text();
      this.add_book_modal = false;

      if (text_data == "fail") {
        this.show_error_modal = true;
        setTimeout(() => {
          this.show_error_modal = false
        }, 500)
      } else {
        this.get_books();
      }
    },
    async add_thought() {
      const book_id = this.books_data.filter(item => item.book_name == this.add_thought_data.book)[0]?.book_id;
      let base_url = `https://erewhon.xyz/book_thoughts/api/add_thought.php?book_id=${book_id}`;

      if (this.add_thought_data.quote != "") {
        base_url += `&quote=${this.add_thought_data.quote}`;
      }

      if (this.add_thought_data.comment != "") {
        base_url += `&comment=${this.add_thought_data.comment}`;
      }

      if (this.add_thought_data.additional_info != "") {
        base_url += `&additional_info=${this.add_thought_data.additional_info}`;
      }

      if (this.add_thought_data.chapter != "") {
        base_url += `&chapter=${this.add_thought_data.chapter}`;
      }

      if (this.add_thought_data.chapter_section != "") {
        base_url += `&chapter_section=${this.add_thought_data.chapter_section}`;
      }

      const fetched = await fetch(base_url);
      const text_data = await fetched.text();
      this.add_thought_modal = false;

      if (text_data == "fail") {
        this.show_error_modal = true;
        setTimeout(() => {
          this.show_error_modal = false
        }, 500)
      } else {
        this.fetch_data();
      }
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

.display_card {
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

.aux_data_view_title {
  color: #fff;
  padding: 5px;
  margin: 5px auto;
}

.aux_data_card {
  text-align: center;
  display: inline-block !important;
  margin: 5px;
}

.filtered_aux_card_data {
  text-align: center;
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

      <va-button outline class="mr-4" @click="current_view = 'thoughts'">
        View thoughts
      </va-button>
      <va-button outline class="mr-4" @click="current_view = 'books'">
        View books
      </va-button>
      <va-button outline class="mr-4" @click="current_view = 'authors'">
        View authors
      </va-button>
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
        <va-button outline class="mr-4" @click="add_author_modal = !add_author_modal">
          Add new author
        </va-button>

        <va-button outline class="mr-4" @click="add_book_modal = !add_book_modal">
          Add new book
        </va-button>

        <va-button outline class="mr-4" @click="add_thought_modal = !add_thought_modal">
          Add new thought
        </va-button>
      </va-navbar-item>
    </template>
  </va-navbar>

  <div class="page" v-if="current_view == 'thoughts'">
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
      <va-card class="display_card" v-for="item in output_data" :key="item" color="#3498db">
        <div class="quote_block" v-if="item.quote">{{ item.quote }}</div>
        <div class="comment_block" v-if="item.comment">{{ item.comment }}</div>
        <div class="additional_info_block" v-if="item.additional_info">{{ item.additional_info }}</div>
        <va-chip class="info_chip" v-if="item.author_name">Author: {{ item.author_name }}</va-chip>
        <va-chip class="info_chip" v-if="item.book_name">Title: {{ item.book_name }}</va-chip>
        <va-chip class="info_chip" v-if="item.chapter">Chapter: {{ item.chapter }}</va-chip>
        <va-chip class="info_chip" v-if="item.chapter_section">Chapter section: {{ item.chapter_section }}</va-chip>
        <va-chip class="info_chip" v-if="item.date_added">Added: {{ item.date_added }}</va-chip>
        <va-chip class="delete_chip" color="danger" v-if="logged_in" @click="delete_thought(item.thought_id)">Delete
        </va-chip>
      </va-card>
    </div>
  </div>

  <div class="page" v-if="current_view == 'authors'">
    <div class="filtered_data filtered_aux_card_data">
      <va-card class="display_card aux_data_card" v-for="item in authors_data" :key="item" color="#3498db">
        <h2 class="aux_data_view_title" v-if="item.author_name">{{ item.author_name }}</h2>
        <va-chip class="info_chip" v-if="item.author_link" :href="item.author_link" target="_blank">Author Link
        </va-chip>
        <va-chip class="delete_chip" color="danger" v-if="logged_in" @click="delete_author(item.author_id)">Delete
        </va-chip>
      </va-card>
    </div>
  </div>

  <div class="page" v-if="current_view == 'books'">
    <div class="filtered_data filtered_aux_card_data">
      <va-card class="display_card aux_data_card" v-for="item in books_data" :key="item" color="#3498db">
        <h2 class="aux_data_view_title" v-if="item.book_name">{{ item.book_name }}</h2>
        <va-chip class="info_chip" v-if="item.author_name">By: {{ item.author_name }}</va-chip>
        <va-chip class="info_chip" v-if="item.book_link" :href="item.book_link" target="_blank">Book Link</va-chip>
        <va-chip class="info_chip" v-if="item.author_link" :href="item.author_link" target="_blank">Author Link
        </va-chip>
        <va-chip class="delete_chip" color="danger" v-if="logged_in" @click="delete_book(item.book_id)">Delete
        </va-chip>
      </va-card>
    </div>
  </div>

  <va-modal v-model="add_author_modal" hide-default-actions overlay-opacity="0.2" size="large">
    <template #header>
      <h2 class="title">Add author</h2>
      <br>
      <va-input class="mb-4" v-model="add_author_data.name" label="Author" />
      <va-input class="mb-4" v-model="add_author_data.link" label="Link" />
    </template>
    <template #footer>
      <va-button @click="add_author">
        Add
      </va-button>
    </template>
  </va-modal>

  <va-modal v-model="add_book_modal" hide-default-actions overlay-opacity="0.2" size="large">
    <template #header>
      <h2 class="title">Add book</h2>
      <br>
      <va-input class="mb-4" v-model="add_book_data.name" label="Title" />
      <va-select class="mb-4" label="Author" :options="authors_data.map(item => item.author_name)"
        v-model="add_book_data.author" searchable />
      <va-input class="mb-4" v-model="add_book_data.link" label="Link" />
    </template>
    <template #footer>
      <va-button @click="add_book">
        Add
      </va-button>
    </template>
  </va-modal>

  <va-modal v-model="add_thought_modal" hide-default-actions overlay-opacity="0.2" size="large">
    <template #header>
      <h2 class="title">Add thought</h2>
      <br>
      <va-select class="mb-4" label="Book" :options="books_data.map(item => item.book_name)"
        v-model="add_thought_data.book" searchable />
      <va-input class="mb-4" v-model="add_thought_data.quote" label="Quote" />
      <va-input class="mb-4" v-model="add_thought_data.comment" label="Comment" />
      <va-input class="mb-4" v-model="add_thought_data.additional_info" label="Additional Info" />
      <va-input class="mb-4" v-model="add_thought_data.chapter" label="Chapter" />
      <va-input class="mb-4" v-model="add_thought_data.chapter_section" label="Chapter Section" />
    </template>
    <template #footer>
      <va-button @click="add_thought">
        Add
      </va-button>
    </template>
  </va-modal>

  <va-modal v-model="show_error_modal" message="Failure" hide-default-actions title="Notice" />
</template>
