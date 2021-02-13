<template>
  <div class="login-page">
    <h1>Login</h1>
    <form class="login-form">

      <div class="form__group field">
        <input v-model="form.username" class="form__field"
               type="text" id="usernameInput" placeholder="Username" required/>
        <label class="form__label" for="usernameInput">Username</label>
      </div>
      <div class="form__group field">
        <input v-model="form.password" class="form__field"
               type="password" id="passwordInput" placeholder="Password" required/>
        <label class="form__label" for="passwordInput">Password</label>
      </div>
      <ul class="form__errors">
        <li v-for="error in errors" :key="error">
          {{ error[0] }}
        </li>
      </ul>
      <button class="form__submit" @click.prevent="login">
        Login
        <span></span><span></span><span></span><span></span>
      </button>
    </form>
    <p>Aren't registered yet?
      <router-link to="/auth/sign-up">Register</router-link>
    </p>
  </div>
</template>

<script>
import authService from "@/services/auth.service";
import router from "@/router";

export default {
  name: "Login",
  data() {
    return {
      form: {
        username: null,
        password: null,
      },
      errors: {}
    }
  },
  methods: {
    async login() {
      let {status, errors} = await authService.login(this.form);
      if (status) {
        router.push({name: 'home'})
      } else {
        console.log(errors)
        this.errors = errors
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.login-page {
  flex-grow: 1;
}
.login-form {
  display: flex;
  flex-direction: column;
  align-items: center;
  row-gap: 10px;

  & .form__errors {
    list-style: none;
    color: darkred;
    padding: 0;
    margin: 5px 0;
    text-align: center;
  }


  & + p a {
    text-decoration: none;
    color: #668ad8;
  }

  & .form__submit {
    display: block;
    --c: Gainsboro;
    color: gray;
    font-size: 16px;
    border: 0.3em solid var(--c);
    border-radius: 0.5em;
    width: 14em;
    height: 3em;
    text-transform: uppercase;
    font-weight: bold;
    font-family: sans-serif;
    letter-spacing: 0.1em;
    text-align: center;
    line-height: 3em;
    position: relative;
    overflow: hidden;
    z-index: 1;
    transition: 0.5s;
    margin: 1em;

    & span {
      position: absolute;
      width: 25%;
      height: 100%;
      background-color: var(--c);
      transform: translateY(150%);
      border-radius: 50%;
      left: calc((var(--n) - 1) * 25%);
      transition: 0.5s;
      transition-delay: calc((var(--n) - 1) * 0.1s);
      z-index: -1;


      @for $i from 1 through 4 {
        &:nth-child(#{$i}) {
          --n: #{$i};
        }
      }

    }

    &:hover {
      color: black;

      & span {
        transform: translateY(0) scale(2);
      }
    }


  }

}

.form__group {
  position: relative;
  padding: 15px 0 0;
  margin-top: 10px;
}

.form__field {
  $primary: #11998e;
  $secondary: #38ef7d;

  font-family: inherit;
  border: 0;
  border-bottom: 2px solid gray;
  outline: 0;
  font-size: 1.3rem;
  color: gray;
  padding: 7px 0;
  background: transparent;
  transition: border-color 0.2s;

  &::placeholder {
    color: transparent;

  }

  &:placeholder-shown ~ .form__label {
    font-size: 1.3rem;
    cursor: text;
    top: 20px;

  }


  &:focus ~ .form__label {
    position: absolute;
    top: 0;
    display: block;
    transition: 0.2s;
    font-size: 1rem;
    color: $primary;
    font-weight: 700;
  }

  &:focus {
    padding-bottom: 6px;
    font-weight: 700;
    border-width: 3px;
    border-image: linear-gradient(to right, $primary, $secondary);
    border-image-slice: 1;
  }

  /* reset input */
  &:required, .form__field:invalid {
    box-shadow: none;
  }

}

.form__label {
  position: absolute;
  top: 0;
  display: block;
  transition: 0.2s;
  font-size: 1rem;
  color: gray;

}
</style>