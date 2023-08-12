a web application where users can create tasks, and able to add sub-tasks,
set due dates, mark tasks as complete, and organize their to-do lists. 
This project involve database management, basic user authentication, and CRUD operations.

database name: ToDoApp
tables list:
- users (id, username, password)
- tasks  (`user_id` int(11) NOT NULL,
  `task_id` int(11) AUTO_INCREMENT PRIMARY KEY,
  `task_name` varchar(255) NOT NULL,
  `parent_task_id` int(11) DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `status` int(2) NOT NULL DEFAULT 0)
