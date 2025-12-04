# Demo Feature Test

## Relationships

- User has many Articles
- An article belongs to a User

## guest

- can view Articles list
- can view detail Article

- cannot create Article without logging in
- cannot update specific Article without logging in
- cannot delete specific Article without logging in

## auth

- User can CRUD Articles
- Articles belong to specific User


## Post

- index
    - list all posts by latest published date
    - return empty paginated collection when no posts exist
    - paginates posts correctly
