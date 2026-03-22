#What is the purpose of the $_FILES superglobal in PHP? 
$_FILES is used to access and store information about files uploaded through a form.

#Why does a form need special settings to upload files? 
A form needs enctype="multipart/form-data" so it can properly send file data instead of just text.

#What function is used to move uploaded files to a folder? 
The function used is move_uploaded_file().

#Why is it important to control where uploaded files are stored?
It’s important to control where files are stored to keep the system secure and organized, and to prevent harmful files from causing problems.