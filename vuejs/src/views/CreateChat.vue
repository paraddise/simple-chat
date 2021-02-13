<template>
  <form class="create-chat-form" @submit.prevent="submit">
    <h2>Create a chat</h2>
    <div class="form__group field">
      <span>Chat name</span>
      <input v-model="form.title" type="text" id="titleInput" required/>
    </div>

    <div class="form__group field">
      <span>Description</span>
      <input v-model="form.description" type="text" id="descInput"/>
    </div>
    <div class="form__group">
      <button type="submit" class="form__submit">Create</button>
    </div>
  </form>
</template>

<script>
import {mapActions} from 'vuex';

export default {
  name: "CreateChat",
  data() {
    return {
      form: {
        title: '',
        description: ''
      },
      errors: {}
    };
  },
  methods: {
    ...mapActions(['createChat']),
    async submit() {
      try {
        let chat = await this.createChat(this.form);
        this.$router.push('/chats', {params: {id: chat.id}})
      } catch (e) {
        alert("Error creating chat, here is data: " + e.response.data)
      }
    }
  },
  computed: {
    hasErrors() {
      return this.formErrors.length > 0;
    }
    ,
    formErrors() {
      let newArray = [];
      newArray.concat(this.errors.title);
      newArray.concat(this.errors.description);
      return newArray;
    }
  }
}
</script>

<style lang="scss" scoped>

.create-chat-form {
  padding: 0 50px;
  flex-grow: 1;
  display: flex;
  flex-direction: column;
  row-gap: 10px;
  align-items: start;
}

.form__errors {
  background-color: #dc3939;
  color: white;
}

.form__group {
  --input-color: #4f5058;
  --input-border: #CDD9ED;
  --input-font-size: 1.3em;
  --input-background: #fff;
  --input-placeholder: #CBD1DC;

  --input-border-focus: #275EFE;

  --group-color: white;
  --group-border: var(--input-border);
  --group-background: #7866f2;
  --group-font-size: 1.4em;

  --group-color-focus: #fff;
  --group-border-focus: var(--input-border-focus);
  --group-background-focus: #6252cc;

  position: relative;
  display: flex;
  width: 100%;

  & > span,
  & > input {
    white-space: nowrap;
    display: block;

    &:not(:first-child):not(:last-child) {
      border-radius: 0;
    }

    &:first-child {
      border-radius: 6px 0 0 6px;
    }

    &:last-child {
      border-radius: 0 6px 6px 0;
    }

    &:not(:first-child) {
      margin-left: -1px;
    }
  }

  & input {
    display: block;
    width: 100%;
    padding: 8px 16px;
    line-height: 25px;
    font-size: var(--input-font-size);
    font-weight: 500;
    font-family: inherit;
    border-radius: 6px;
    -webkit-appearance: none;
    color: var(--input-color);
    border: 1px solid var(--input-border);
    background: var(--input-background);
    transition: border .3s ease;

    &::placeholder {
      color: var(--input-placeholder);
    }

    &:focus {
      outline: none;
      border-color: var(--input-border-focus);
    }

    position: relative;
    z-index: 1;
    flex: 1 1 auto;
    margin-top: 0;
    margin-bottom: 0;
  }

  & > span {
    text-align: center;
    padding: 10px 15px;
    font-size: var(--group-font-size);
    line-height: 25px;
    color: var(--group-color);
    background: var(--group-background);
    border: 1px solid var(--group-border);
    transition: background .3s ease, border .3s ease, color .3s ease;
  }

  &:focus-within {
    & > span {
      color: var(--group-color-focus);
      background: var(--group-background-focus);
      border-color: var(--group-border-focus);
    }
  }

  .form__submit {
    margin-top: 100px;
    background-color: var(--group-background);
    border: 0;
    width: 100%;
    padding: 10px;
    font-size: var(--group-font-size);
    color: var(--group-color);
    border-radius: 5px;

    &:hover {
      background-color: var(--group-background-focus);

    }
  }
}
</style>