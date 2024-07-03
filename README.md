# SFSessions

<img style="width:100%" src="https://github.com/Flibouche/SfSessions/blob/main/images/SfSessions1.png">

This project is an application designed for training center administrators to manage training sessions. The application enables administrators to handle training sessions, modules, categories, and student registrations.

## Installation

### Prerequisites

- PHP >=8.2
- Composer
- Symfony CLI

### Setup

1. Clone the repository:

    ```bash
    make sfsessions
    ```

2. Name the repo

3. Composer install
   
   ```bash
    composer install
    ```
   
## Project Contents

- **Administrator Authentication** : Access to the application is restricted to authorized administrators.
- **Training Session Management** : Administrators can create, view, and modify training sessions. Each session includes a set number of seats, start and end dates, and a program composed of modules categorized by specific categories.
- **Module and Category Management** : Administrators can manage modules and their associated categories. Modules can be assigned to different training sessions.
- **Student Registration** : Administrators can add students and enroll them in existing training sessions.
- **Information Display** : The application displays available training sessions, the program for each session (modules + categories), the list of students registered for each session, as well as the list of students and their respective registrations.

## Features

- **Light/Dark Mode** : Added a Light/Dark mode with TailwindCSS/Flowbite.

## Technologies Used

- Symfony
- HTML, Twig, PHP, JavaScript, AlpineJS, JQuery
- TailwindCSS, Flowbite for styling
- Database: MySQL
- Tools: HeidiSQL for database management
- Looping for MCD/MLD
- Trello for work organization

## More illustrations

<img style="width:100%" src="https://github.com/Flibouche/SfSessions/blob/main/images/SfSessions2.png">
<img style="width:100%" src="https://github.com/Flibouche/SfSessions/blob/main/images/SfSessions3.png">
<img style="width:100%" src="https://github.com/Flibouche/SfSessions/blob/main/images/SfSessions4.png">
<img style="width:100%" src="https://github.com/Flibouche/SfSessions/blob/main/images/SfSessions5.png">
