README.txt

@author James Fulford
@email james.patrick.fulford@gmail.com
@started October 2 2016
@lastedit November 18 2016


Instructions:

	How to view:
		1. View cache.html in the filesystem (double click) or through the server.
			For my system, use url: localhost/~jamesfulford/Capstone/cache.html

	How to update with new course data:
		1. Replace “Courses.xml” with the new .xml file.
		2. Run update.php (done by sending a request to the server)
			For my system, use url: localhost/~jamesfulford/Capstone/update.php

FAQ:
	Help! I didn’t mean to overwrite the cache with data I don’t like!
		A: The last save should be stored in the backache. Check inside the Resources file for backcache.html, and if that looks better, replace it.

	Help! The cache.html file looks ugly in my browser!
		A: Try running update.php again. If it still looks ugly, then there might be a problem either with one of the scripts in the Resources folder, or with our sense of taste.

	Help! The filters aren’t working!
		A: If you have disabled javascript, please enable it. Otherwise, you cannot fully enjoy the full power of this system.


Contact:
	James Patrick Fulford (creator)
	james.patrick.fulford@gmail.com

	Ed Cauthorn (data generator)
	ecauthorn@ccsnh.edu

Summary:

	Parsing XML using PHP, crafting xHTML to store on server. Clients can request the pre-prepared html file, which means parsing happens only when a new XML file is generated instead of on every request.

	Client must have Javascript enabled.


Requirements set forth by Dr. Tahir:
    	1. Have the rows show up in different colors based on Seats_Consumed/Total_Seats
        	# Better: progress bars show how full a course is.
    	2. Have drop-down menus for filtering
		# Currently, I have multiple filters which can:
        		# Filter by department.
			# Filter Online vs. In Class (or both)
			# Filter out full courses
			# Search for string in title, CRN, or Code
   	3. Have it look decent
        	# It is decent.
		

