# Urrea charts
Display charts from customers sales

## How to use it

#### #1 To clone and run this application, you'll need <a href="https://git-scm.com" target="_blank">Git</a> and <a href="https://www.apachefriends.org/es/index.html" target="_blank">Xampp</a> installed on your computer. From your command line:

```bash
# Clone this repo
$ git clone https://github.com/MiloJuarez/urrea-dashboard.git

# Move to cloned repo
$ cd urrea-dashboard

# Create .env file
$ cp .env.example .env

# Only for windows command line
> copy .env.example .env 

```

#### #2 Database setup

Open a command line and type the following. Note: MySQL service must be running.

* 2.1 Log into mysql and create a new database.

```bash
# Open a command line terminal and move to mysql directory
C:\> cd xampp\mysq\bin

# Login into MySQL
C:\xampp\mysq\bin>mysql -u root -p
password:
mysql>create database <database>

# Quit from MySQL
mysql>\q
 
```
* 2.2 In the project you have cloned, you'll find the <i>urrea.sql</i> file, use it to import the data as following:
```
C:\xampp\mysq\bin>mysql -u root -p <database> < path/to/urrea-dashboard/urrea.sql
```
#### #3 Next, copy this project to your <strong><i>htdocs</i></strong> folder, usually located at: <strong><i>C:\xampp\htdocs</i></strong>.
<strong>Note:</strong> Make sure Apache service is running, if not, enable it from the XAMPP Control Panel app.

Finally, open your browser and type the next url: http://localhost/urrea-dashboard

