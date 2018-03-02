// import axios from "axios";
import auth from "./auth";
export default {
    requireAuth(to, from, next) {
        if (!auth.isLoggedIn()) {
            next({
                path: '/login',
            });
        } else {
            next();
        }
    },

    logout() {
        clearUserProfile();
        document.location.href = '/api/auth/logout';
    },

    isLoggedIn() {
        const idToken = getUserId();
        return !!idToken;
    },

    fetchUserData() {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/users/info")
                .then(response => {
                    localStorage.setItem('USER_DATA', JSON.stringify(response.data))
                    resolve(JSON.stringify(response.data));
                })
                .catch(error => {
                    clearUserProfile();
                    reject({"message":"user is not logged in"});
                });
        });
    },
    signup(formData) {
        return new Promise((resolve, reject) => {
            axios
                .get("/api/server.php")
                .then(response => {
                    localStorage.setItem('USER_DATA', JSON.stringify(response.data))
                    resolve(JSON.stringify(response.data));
                })
                .catch(error => {
                    clearUserProfile();
                    reject({"message":"user is not logged in"});
                });
        });
    }
}


function getUserId() {
    if (!localStorage['USER_DATA']) return;

    const jUser = JSON.parse(localStorage['USER_DATA']);
    return jUser.id;
}

function clearUserProfile() {
    if (!localStorage['USER_DATA']) return;

    localStorage.removeItem('USER_DATA');
}