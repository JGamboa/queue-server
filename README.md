## Prerequisites
- MySQL installed
- Redis Server installed
- Composer installed
- Nodejs installed
- PHP >= 7.2

## About the installation

- In your MySQL server create a database for this project, use the name of your preference.
- Open a terminal, and go to the project directory and execute "composer install"
- Configure your .env, to do that execute in your terminal "cp .env.example .env", the .env already comes with the CACHE_DRIVER and QUEUE_CONNECTION set on redis
  <br>
  You have to Set up your Database environment information:<br>
  DB_CONNECTION=mysql<br>
  DB_HOST=YourIp<br>
  DB_PORT=YourPort<br>
  DB_DATABASE=YourDatabaseName<br>
  DB_USERNAME=YourDatabaseUsername<br>
  DB_PASSWORD=YourDatabasePassword<br>
- Then execute the command "php artisan key:generate"
- Then execute the command "php artisan migrate --seed"
- Then execute the command "php artisan passport:install"<br>
  You'll get something like the next output(just and example):<br>
  Encryption keys generated successfully.<br>
  Personal access client created successfully.<br>
  Client ID: 1<br>
  Client secret: gy5ceS6mnib6LlSjGd61b9yQKncRvrbEsmpz0FBS<br>
  Password grant client created successfully.<br>
  Client ID: 2<br>
  Client secret: MdjeWF8S3ndSMp8JqAL3eHSq6PNKQHJi2d3Xc8zf<br>
  Copy the client secret from the Client ID: 2 and replace it on the .env in the PASSPORT_CLIENT_SECRET: <br>
  
- Next run in your terminal in the project directory the command : "npm install && npm run dev"  


## Start the test server
  With this instructions we will start the test server, and two job workers
- Here i will ask you to open 4 terminals on the project directory <br>
  The first terminal will be use to execute the command "php artisan serve", this will turn on the PHP Builtin Webserver
  The second terminal will be use to execute the command "php artisan websocket:serve", this will turn on the websocket server for realtime notifications
- The third terminal will be use to execute the command "php artisan queue:work --queue=high,default" 
- The fourth terminal will be use to execute the command "php artisan queue:work --queue=high,default"

- Open your terminal at http://127.0.0.1:8000
- Login with one of this users :<br/>
 testuser@example.com <br/>
 testuser2@example.com <br/>
 testuser3@example.com <br/>
 The password is password"


## Information
- Laravel takes care that 1 worker (job processor) only process one job at a time
- I defined 2 queues , high for the jobs, and default for the notifications. The worker will process the high queue first and then process the default(low priority queue)

## Endpoints
Every endpoint thats not the loggin need to use the header Authorization:<br>
Authorization: Bearer ${token}
## Axios configuration for testing
  ```typescript
import axios from 'axios'

const domain = "http://127.0.0.1:8000/api";

export const apiClient = axios.create({
  baseURL: domain,
})


/*
 * Add a request interceptor
 @param config
*/
apiClient.interceptors.request.use(
    function(config) {
        const token = window.localStorage.getItem('token');
        if (token != null) {
            config.headers.Authorization = `Bearer ${token}`;
        }
        return config;
    },
    function(error) {
        return Promise.reject(error.response);
    }
);
```

## Login process vue example
**Login**
  Returns json data of the access_token
----
* **URL**
  http://127.0.0.1:8000/api/auth/login  
   
 * **Method:**
 
   `POST`
   
 *  **URL Params**
 
     None
 
 * **Data Params**
 
    **Required:**
  
    `username=[string]`
    
    **Required:**
      
    `password=[string]`
 
 * **Success Response:**
 
   * **Code:** 200 <br />
     **Content:** 
     ```json
         {
           "token_type": "Bearer",
           "expires_in": 31622400,
           "access_token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIyIiwianRpIjoiY2U5MTBiNzZiNzdjZjI4NjI0NTVjMjVmYmU0MDlmYTI3OGFjZDY3YjYwODhiMDhkOTYyM2U1NjFkYTNhNTA4MGVlYzk3ZmVmNDI0NDFlY2MiLCJpYXQiOjE1ODA2NDkxNzgsIm5iZiI6MTU4MDY0OTE3OCwiZXhwIjoxNjEyMjcxNTc4LCJzdWIiOiIxIiwic2NvcGVzIjpbXX0.fmrMK0bH_JsGfDWg3JTNtave0LZnfKij04MpiqqMv9eTbcq09RMurtSX3uQfZUs3wpE0pemdXQhwXZwQpjV4LdWUkxslFxsHAq7PrFATaQBS5Kf8ydREoVwHjD8WC7axszfQjkG_X90jyW3R17lcJmeAvGnJkLdgo_pM86UDR0SHimSflnMNRKvFU5aPsDxYzOpgeMHqkMVaq1M0pV2gvuiZECtKV3VUBn6p5FEWIypDbTCLw0QILJkiyXr62UD6rwFJR8aPTSncSxlLSONSNXPTL-TQC_dCbkXdQEnc6UpAj9JE48HFqgpA4VmrbNNzqXtQrRuHwkQSEsqd6rFYkHsuuvVE88j98_lyzVqrdJw0h080r6G86m9lGmw3LjPTxMvtyns0cNAI2ECdtMPNJR-AH4kyosl699S6uTrDz6rwrPg9JqZe6cf4Pvd8SedM3q0eZcSTBStsn_0qUsV7KaymR3YFzFTyatYQnMFmmdLnDzuGl9tQ5TICASY7fBqQaJqBG_jccUoog9ky7U5NXr8l1iengmRkV8H25Tp3OHyDiDhMhLRm8ebzKna533-AVRngtEEbWSsA1PlXxz8s7-4qylLc50ZOfvwxLU-ZzT8Vx8ZbJggwgoujae7stHrrc_jhSPD3u_QLcc7s6UetDHuK02dENlUzg-PlLBhSN5s",
           "refresh_token": "def502007f3c7e1da7cfdaf2cafc2949873b4b6489eab84608430bb936fe0c9f24631a9225ba9aed75f0b9dd0e119f1f706c96f46a98b496796ea3ece6789a6bbc189019eef031ca03042e33c367583928cf1aa4bd32f0f8def81d78a16ecc2e4328dba0e78951e8fc0886cef34d48596638a07901e8507539fbaa990af3024f390f7718453aa97d351916923aa5b19048e3773a8d32d0ac0571b90028249a00d6dbe63d674486d42a01a1c8afce025a2d10aa50a757a73855452f4f12ca4af8e8b3dd5449283dd14b2106f003e6f73e7005bf2d7f057c25415b5c6600d88d86d2c28bff98cb54dee2bf8cd00700f127f4c5aa7a3c1d8c07d18f01026d1e58d2d5ee3b1675422a4532f83430ff4d1898fb8c95e928f1da6190608e9c3c4ea2d0726aa3b654e0dab61606c46b8cd5ceba25018bc0dbf43ab70547721a178ba76522f9f57ac574e105510d3c687c774974192284bef31e49b11c2db5cecc1892a7fe"
          }
     ```
  
 * **Error Response:**
 
   * **Code:** 400 BAD REQUEST <br />
     **Content:** 
     ```json
         {
           "status": "error",
           "message": "Your credentials are incorrect. Please try again"
          }
 
   OR
 
   * **Code:** 400 BAD REQUEST <br />
     **Content:** 
     ```json
         {
           "status": "error",
           "message": "Invalid Request. Please enter a username or a password."
         }
     ```

* **Sample Call:**

  ```typescript
    import * as API from "./axios.js";
    function login(){
        return new Promise((resolve, reject) => {
            API.apiClient.post("http://127.0.0.1:8000/api/auth/login", { username: 'testuser@example.com', password: 'password' })
                .then(response => {
                    window.localStorage.setItem("token", response.data.access_token);
                    resolve(response.data.access_token);
                })
                .catch(error => {
                    reject(error.data);
                });
        });
    }       
  ```

**Show Job**
----
  Returns json data about a single job.

* **URL**

  http://127.0.0.1:8000/api/jobs/:id

* **Method:**

  `GET`
  
*  **URL Params**

   **Required:**
 
   `id=[integer]`

* **Data Params**

  None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:** 
    ```json
    {
      "id": 50,
      "queue_job_id": "3qizo3bIhw4skquhXmbrnWDsSfaED73m",
      "submitter_id": 1,
      "processor_id": 31785,
      "command": "ls",
      "state": "success",
      "started_at": "2020-02-02 11:00:31",
      "finished_at": "2020-02-02 11:00:31",
      "created_at": "2020-02-02 11:00:25",
      "updated_at": "2020-02-02 11:00:31"
    }
    ```
 
* **Error Response:**

  * **Code:** 404 NOT FOUND <br />
    **Content:** 
    ```json
    {"message": "No query results for model [App\\Models\\Job] 5012" }
    ```

  OR

  * **Code:** 403 FORBIDDEN <br />
    **Content:** 
    ```json
    {"message": "You can only see the information of the jobs that you own" }
    ```

* **Sample Call:**

  ```ajax
    $.ajax({
      url: "/jobs/1",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```


**Show Jobs**
----
  Returns json data about a the jobs of the user with pagination

* **URL**

  http://127.0.0.1:8000/api/jobs

* **Method:**

  `GET`
  
*  None

* **Data Params**

   None

* **Success Response:**

  * **Code:** 200 <br />
    **Content:**
    ```json
    {
      "current_page": 1,
      "data": [
          {
              "id": 73,
              "queue_job_id": "GugtRXQ8LjTXweDIJjMRkSOzQslIdmoH",
              "submitter_id": 1,
              "processor_id": 31995,
              "command": "ls",
              "state": "success",
              "started_at": "2020-02-02 12:39:58",
              "finished_at": "2020-02-02 12:40:03",
              "created_at": "2020-02-02 12:39:53",
              "updated_at": "2020-02-02 12:40:03"
          },
          ...
      ],
      "first_page_url": "http://127.0.0.1:8000/api/jobs?page=1",
      "from": 1,
      "last_page": 8,
      "last_page_url": "http://127.0.0.1:8000/api/jobs?page=8",
      "next_page_url": "http://127.0.0.1:8000/api/jobs?page=2",
      "path": "http://127.0.0.1:8000/api/jobs",
      "per_page": 10,
      "prev_page_url": null,
      "to": 10,
      "total": 73
    }
    ```
 
* **Error Response:**

  * **Code:** 404 NOT FOUND <br />
    **Content:** `{"message": "No query results for model [App\\Models\\Job] 5012" }`

  OR

  * **Code:** 403 FORBIDDEN <br />
    **Content:** `"message": "You can only see the information of the jobs that you own" }`

* **Sample Call:**

  ```ajax
    $.ajax({
      url: "/jobs",
      dataType: "json",
      type : "GET",
      success : function(r) {
        console.log(r);
      }
    });
  ```
  
  **Create Job**
  ----
    Create a job and returns the data
  
  * **URL**
  
    http://127.0.0.1:8000/api/jobs
  
  * **Method:**
  
    `POST`
    
  *  None
  
  * **Data Params**
  
    **Required:**
      
    `command=[string]`
  
  * **Success Response:**
  
    * **Code:** 200 <br />
      **Content:** 
      ```json
      {
          "command": "ls",
          "submitter_id": 1,
          "updated_at": "2020-02-02 13:32:49",
          "created_at": "2020-02-02 13:32:49",
          "id": 74,
          "state": "pending"
      }
      ```
   
  * **Error Response:**
  
    * **Code:** 422 Unprocessable entity <br />
      **Content:** 
      ```json
      {
         "message": "The given data was invalid.",
         "errors": {
            "command": [
               "The command field is required."
             ]
          }
      }
       ```
    OR
  
    * **Code:** 401 Unauthorized <br />
      **Content:** `
      ```json
      {
          "message": "Unauthenticated."
      }
      ```
  
  * **Sample Call:**
  
    ```typescript
      function save () {    
      axios.post('/jobs', { command: 'ls' })
          .then(response => {
              this.editedItem = response.data;
              this.close();
          })
          .catch( error => {
              
          });
  },
    ```
