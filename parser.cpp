//course is used to store groups
//student is used to store employees, their position and avaliability

// reading a text file
#include <iostream>
#include <fstream>
#include <string>
#include <sstream>
#include "struct.h"

using namespace std;

int main () 
{
  int i;
  int k;
  /*Initialize structure variables*/
  courses cour;
  students studs;
  int choice = -1;
  /*Initialize structure counters to 0*/
  cour.course_cnt=0;
  studs.student_cnt=0;
  
  /*Initalize all caps equal to CHUNCKSIZE*/
  cour.cour_cap = studs.stud_cap = CHUNKSIZE;
  /*initalize memory for each structure*/
  cour.cour_list = (course *) malloc (sizeof(course) * CHUNKSIZE);
  studs.stud_list = (student *) malloc (sizeof(student) * CHUNKSIZE);
  
  string line;
  ifstream myfile ("schedularInput.txt");
  if (myfile.is_open())
  {
    while ( getline (myfile,line) )
    {
    	istringstream iss(line);
    	string word;
    	//sift through each line by whitespace
    	while( iss >> word )
    	{
    		//list of groups coming
    		if (word == "groups" )
    		{
    			while( iss >> word )
    			{
  					/*Reallocate memory if more is needed*/  
  					if(cour.course_cnt == cour.cour_cap)
  					{
        				course *temp;
        				temp = (course *) realloc(cour.cour_list, sizeof(course) * (cour.cour_cap + CHUNKSIZE));
        				cour.cour_cap += CHUNKSIZE;
        				cour.cour_list = temp;
  					}
  					
  					/*Store name*/
  					 cour.cour_list[cour.course_cnt].name = word;
  					/*Add one to the course count*/
  					cour.course_cnt += 1;
				}
    			
    		}
    		//employee info coming
    		if (word == "people" )
    		{
    			int i = 0;
    			
    			/*Reallocate memory if more is needed*/
  				if(studs.student_cnt == studs.stud_cap)
  				{
        			student *temp;
        			temp = (student *) realloc(studs.stud_list, sizeof(student) *(studs.stud_cap + CHUNKSIZE));
       				studs.stud_cap += CHUNKSIZE;
       				studs.stud_list =temp;
 				 }
    			
    			while ( iss >> word )
    			{
    				//input first name
    				if( i == 0 )
    				{
    					studs.stud_list[studs.student_cnt].first = word;
    					
    				}
    				//input last name
    				if( i == 1)
    				{
    					studs.stud_list[studs.student_cnt].last = word;
    				}
    				//input positon/group
    				if( i == 2)
    				{
    					studs.stud_list[studs.student_cnt].pos_pref = word;
    				}
    				//monday schedule
    				if( i == 3)
    				{
    					studs.stud_list[studs.student_cnt].work[0][0] = word;
    				}
    				//Tuesday schedule
    				if( i == 4)
    				{
    					studs.stud_list[studs.student_cnt].work[0][1] = word;
    				}
    				//Wednesday schedule
    				if( i == 5)
    				{
    					studs.stud_list[studs.student_cnt].work[0][2] = word;
    				}
    				//thursday schedule
    				if( i == 6 )
    				{
    					studs.stud_list[studs.student_cnt].work[0][3] = word;
    				}
    				//friday schedule
    				if( i == 7 )
    				{
    					studs.stud_list[studs.student_cnt].work[0][4] = word;
    				}
    				//Saturday schedule
    				if( i == 8 )
    				{
    					studs.stud_list[studs.student_cnt].work[0][5] = word;
    				}
    				//sunday schedule
    				if( i == 9 )
    				{
    					studs.stud_list[studs.student_cnt].work[0][6] = word;
    				}

    				i++;
    			}
    			
    			
    			cout << endl << studs.stud_list[studs.student_cnt].first << " " << 
    			studs.stud_list[studs.student_cnt].last;
    			cout << endl << studs.stud_list[studs.student_cnt].pos_pref << endl;
    			int h;
    			cout << "Availability" << endl;
    			cout << "------------------------------------------------------------------------------------" << endl;
    			cout << "   Monday  |  Tuesday  | Wednesday |  Thursday |   Friday  |  Saturday |   Sunday  |\n";
    			
    			for(h = 0; h < 7; h++)
    			{
    				if( studs.stud_list[studs.student_cnt].work[0][h] == "0")
    				{
    					cout << "  X        ";
    				}
    				if( studs.stud_list[studs.student_cnt].work[0][h] == "1")
    				{
    					cout << "Aval: 1st  ";
    				}
    				if( studs.stud_list[studs.student_cnt].work[0][h] == "2" )
    				{
    					cout << "Aval: 2nd  ";
    				}
    				if( studs.stud_list[studs.student_cnt].work[0][h] == "3" )
    				{
    					cout << "Aval: Both ";
    				}
    				cout << "|";
    			}
    			cout << endl << endl;
    			
    			
    			
    			studs.student_cnt += 1;
    		}
       	}
    }
  
    myfile.close();
  }

  else cout << "Unable to open file"; 
  
   int j;
  /*Loop through course struct and print cooresponding info*/
  for ( j=0; j < cour.course_cnt; j++)
  {
  	cout << "Group Name: " <<  cour.cour_list[j].name;
  	cout << endl << endl;
  }
  
  
  
  //free memory
  free(cour.cour_list);
  free(studs.stud_list);


  return 0;
}
