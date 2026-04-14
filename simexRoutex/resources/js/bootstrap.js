import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

const url = import.meta.env.VITE_APP_URL;

window.axios.defaults.baseURL = `${url}/api/`;

const token = localStorage.getItem('token');

if (token) {
  axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
}
