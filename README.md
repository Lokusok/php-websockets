# Realtime Chat on PHP (PoC)

- `/server` - серверная часть (PHP)
- `/client` - клиентская часть (JS/Vue)

---

## Для запуска

### Сервер
0. Нужен Docker
1. `cd server`
2. `make setup`
3. `make start` (после того как появится `vendor`)

### Клиент
0. Нужен node.js
1. `cd client`
2. `npm i`
3. `npm run dev`

---

## Технологии, использованные при разработке

![PHP](https://img.shields.io/badge/php-%23777BB4.svg?style=for-the-badge&logo=php&logoColor=white)![SQLite](https://img.shields.io/badge/sqlite-%2307405e.svg?style=for-the-badge&logo=sqlite&logoColor=white)![Docker](https://img.shields.io/badge/docker-%230db7ed.svg?style=for-the-badge&logo=docker&logoColor=white)![Vue.js](https://img.shields.io/badge/vuejs-%2335495e.svg?style=for-the-badge&logo=vuedotjs&logoColor=%234FC08D)![JavaScript](https://img.shields.io/badge/javascript-%23323330.svg?style=for-the-badge&logo=javascript&logoColor=%23F7DF1E)