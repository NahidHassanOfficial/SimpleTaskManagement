# 📋 Simple Task Management System

A simple task management application built for a technical assignment.

---

## Preview & Demo

- Video: https://drive.google.com/file/d/1nv5iB5jJwpW2je1vzTFmW42P2TNyjzQk/view?usp=drive_link
<img width="1919" height="959" alt="image" src="https://github.com/user-attachments/assets/5b2eba25-3aec-4ee9-8ea1-7e671e7575b1" />
<img width="1919" height="959" alt="image" src="https://github.com/user-attachments/assets/89967ffd-3ea7-4775-a1ed-274b4574225d" />



---

## Tech Stack

- Backend: Laravel 12
- Frontend: Inertia.js + Vue 3
- Styling: Tailwind CSS
- Testing: Pest
- Database: MySQL

---

## Features

- Create, update, and delete tasks
- Filter tasks by task id, status, severity, assignee, and date
- Dashboard with task summary
- Auto-generated task codes (e.g., `TASK-0001`)
- Select users for assigned_to and created_by from dropdown (As no auth mechanism is used)

---

## Decisions

- No authentication  
  Pre-seeded users are used and selected via dropdown to keep focus on task logic

- Resource controllers  
  Used for standard and simple CRUD structure

- Model scopes (filtering)  
  Keeps filtering logic reusable and controllers clean

- Enums (status and severity)  
  Prevents invalid values and ensures consistency

- Business rules
    - Completed tasks cannot be updated
    - Only pending tasks can be deleted

- Soft deletes  
  Prevents permanent data loss

- Testing (Pest)
    - Unit tests for model logic
    - Feature tests for CRUD flow

---

## Setup

```bash
git clone https://github.com/NahidHassanOfficial/SimpleTaskManagement.git
cd SimpleTaskManagement

composer install
npm install

cp .env.example .env
php artisan key:generate

php artisan migrate --seed

php artisan serve
```
