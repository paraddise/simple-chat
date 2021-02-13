<template>
  <div class="signup-page">
    <h1>Sign Up</h1>
    <form class="login-form">

      <div class="form__group field">
        <input v-model="form.username" class="form__field"
               type="text" id="usernameInput" placeholder="Username" required/>
        <label class="form__label" for="usernameInput">Username</label>
        <p class="field__error" v-for="error in errors.username" :key="error">{{ error }}</p>
      </div>

      <div class="form__group field">
        <input v-model="form.email" class="form__field"
               type="email" id="emailInput" placeholder="Email" required/>
        <label class="form__label" for="emailInput">Email</label>
        <p class="field__error" v-for="error in errors.email" :key="error">{{ error }}</p>
      </div>

      <div class="form__group field">
        <input v-model="form.password" class="form__field"
               type="password" id="passwordInput" placeholder="Password" required/>
        <label class="form__label" for="passwordInput">Password</label>
        <p class="field__error" v-for="error in errors.password" :key="error">{{ error }}</p>
      </div>

      <div class="form__group field">
        <input v-model="form.password_repeat" class="form__field" type="password" id="passwordRepeatInput"
               placeholder="Password" required/>
        <label class="form__label" for="passwordRepeatInput">Password Repeat</label>
        <p class="field__error" v-for="error in errors.password_repeat" :key="error">{{ error }}</p>
      </div>

      <button class="form__submit" @click.prevent="register">
        Register
        <span></span><span></span><span></span><span></span>
      </button>
    </form>
    <p>Already have an account?
      <router-link to="/auth/login">Login</router-link>
    </p>
  </div>
</template>

<script>
import authService from "@/services/auth.service";
import router from "@/router";
import httpClient from "@/services/http.service";

export default {
  name: "SignUp",
  data() {
    return {
      form: {
        username: '',
        email: '',
        password: '',
        password_repeat: '',
      },
      errors: {},
    }
  },
  methods: {
    async register() {
      if (this.form.password === '' || this.form.password !== this.form.password_repeat) {
        this.errors.password_repeat = ['Passwords should match']
        return;
      }
      try {
        const {status, data} = await httpClient.post('/user/sign-up', this.form);
        if (status === 200) {
          authService.setUser(data)
          router.push({name: 'home'})
        } else {
          this.errors = data.errors;
        }
      } catch (e) {
        this.errors = e.response.data.errors
      }
    }
  }
}
</script>

<style lang="scss" scoped>

.signup-page {
  flex-grow: 1;
}
.login-form {
  display: flex;
  flex-direction: column;
  align-items: center;
  row-gap: 10px;

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

  & .field__error {
    font-size: 0.8em;
    color: darkred;
    text-align: center;
    word-wrap: normal;
  }
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