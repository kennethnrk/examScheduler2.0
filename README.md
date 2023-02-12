# Exam Scheduler 2.0
## Multi-department and variable examhall size exam Scheduler

    This exam scheduler was designed keeping in mind the faculty point of view.
The faculty logs in and inputs the required data regarding the examination. 
On submission of this data, the faculty is redirected to the page on which, 
the schedule of the exa is displayed onto a calendar. On clicking any of the courses, 
the user(faculty) is redirected to the page that will display the seating arrangement of that particular course.
This seating arrangement is displayed in agraphical manner. The student details are displayed, 
along with an icon to represent the seat.

    Note: This web-application has been created considering the term test structure of K. J. Somaiya Polytechnic.
          Due to this limitation, it may not fit the structure of all colleges/schools, 
          but it can be easily modified to suit the specific need of any particular school/college easily.

    This web-application has been created using PHP, along with HTML and CSS, with a few splashes of javascript.
The scheduling is done using PHP. The object oriented and dynamic nature of this language played an important part in the scheduling process.
The website contains 15 php pages along with 3 CSS files and 1 javascript file. 
These pages/files have been listed below in alphabetical order:

1. bottom.php
The footer file, included in most pages. This page has to be used in tandem with the top.php page. 
It provides a footer to the page along with some of the closing tags for the opening tags used in the top.php page.


2. calendar.css
Specifies the design of the calendar.php page that is displayed on the viewschedule.php page

3. calendar.php
This page is used to display the scheduled examinations in a graphical user interface of a calendar. 
The calendar displays the exams scheduled in the cells corresponding to the date of the exam. 
Along with this it also links the exam schedules to the viewfullschedule.php and the viewseating.php pages.

4. checkdetails.php
This page isn’t visible to users, its job is to accept and check the format of the data inputted in the inputdetails.php page. 
After the data is verified, it's passed on to the main.php page.

5. connection.php
This page is required on each and every page of the website. 
This page contains the required code in order to connect to the database. 
It also contains the functions that are used frequently throughout the web pages.

6. delete.php
This page is used to clear the database along with the files directory of the existing scheduled examinations.

7. header.js
This page is used to provide functionality to the collapse/expand button for the mobile version of the header,
 along with the go to top button.

8. index.php
This is the first page the user visits on arrival, it’s function is to redirect the user to the inputnumbers.php or the login.php page, 
depending on if the session variable has been set.

9. inputdetails.php
This file is used to input the details of the halls, along eith the details of the departments.
These details include: course list as well as student list for each department.
These details are inputted using .csv files.

10. inputnumbers.php
The user has to input the details regarding the exam that has to be scheduled on this page. 
This is the primary input page of the exam scheduler. It is the first page the user is directed to after logging in.
The user needs to input no of departments, term(even or odd), along with the examination dates on this page.

11. login.css
Contains the design details of the login.php page

12. login.php
This page is responsible for authenticating the credentials of the user in order to verify that they have the permission to use this scheduler. 
If at all the user does not login they cannot access any of the other pages.

13. logout.php
This page logs the user out of the page by unsetting the required session variables.

14. main.php
This page isn’t visible to the user, its job is to schedule the examination on the basis of the data passed to it by the checkdetails.php page. 
This is the most important and most complex page in the whole website. 
It takes into account various factors in order to create a well planned schedule by assigning the subslots to the courses as well as assigning the seats to the students. 

15. style.css
Contains the design details of the rest of the pages of the entire website.

16. top.php
This page is used to display the header.
It’s header contains a navigation bar to some of the important pages of the website. 
It is mobile friendly and stacks vertically when the screen width is below 991px.

17. viewfullschedule.php
In cases where more than two examinations are scheduled on one day, the calendar.php page creates a view all button which links to this page. 
This page displays all the examinations on a particular date along with their details.

18. viewschedule.php
This page has no content of its own. It is used as the framework for displaying the calendar.php page. 
The calendar.php page is implemented by creating an object of the calendar class in the viewschedule.php page.

19. viewseating.php
This page is used to view the seating arrangement of a particular examination. 
The seating arrangement is displayed in a graphical manner along with seat icons to represent the seats.
