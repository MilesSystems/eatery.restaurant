#include <stdio.h>
#include <stdlib.h>
#include <string.h>
using namespace std;

#define CHUNKSIZE 25


/*Student structure
really the employee structure*/
typedef struct
{
   string first;
   string last;
   string pos_pref;
   string work[1][7];
}  student;


/*Course Structure
really the group structure*/
typedef struct
{
  string name;
} course;




/*Students Structure
employee structure*/
typedef struct
{
  int student_cnt;
  int stud_cap;
  student *stud_list;   /*Array of type student structure*/
} students;


/*Courses Structure
group structures*/
typedef struct
{
  int course_cnt;
  int cour_cap;
  course *cour_list;    /*Array of type course structure*/
} courses;


