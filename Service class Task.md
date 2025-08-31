## Service class (Laravel) + UI (React) 

Create a service class to pull the promotional data, create a small Ul with React to utilize this data. 

---

1.  Use the structure from task #1 one to create DB tables on backend. 
2.  Create a service class that would get the promotion data. 
    1.  Retrieved data should be wrapped in DTOs which should be framework agnostic. 
    2.  Handle exceptions and allow for developers to hook into failed responses (custom to the service class). 
    3.  Service class will most likely grow with time and could get changed to a different service, ensure best practices are used to deal with that. 
3.  Create a single Ul page with React to see the standings for each leaderboard. 
    1.  Optimize the data loading so that users don't have to wait for all data to come before page is shown to them. 
    2.  Allow user to refresh the data loaded. 
4.  Add README docs to show examples and how to use the service class. 
5.  Make sure to write understandable commits, use branches and merge requests. 
6.  Code should be readable and easy to understand. 

---

**Bonus points:** as promotion won't have much data coming in frequently, optimize the operation since this service class would normally be utilized in a high traffic environment. 