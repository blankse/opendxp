# Build Role & Rights System for Frontends

A common use case for OpenDxp applications are portals with user logins and a complex permission structure like for
 example b2b portals. 
 
One way of archiving this is to leverage Symfony Security functionality with access control configuration
and ACLs as described [here](https://symfony.com/doc/current/security.html).
   
But, it might be handy to empower admin users to configure and maintain user permissions themselves. And therefore, 
OpenDxp data objects are also a great choice.
Just create an user object and define permissions directly on it or via relations to other permission role objects 
 that are assigned to the user object. 
 
So how to integrate OpenDxp objects, a roles & rights system and Symfony best practise to implement complex permission
  structures for Frontends based on OpenDxp? 


**Solution**

##### Setup User Authentication with Symfony Security and OpenDxp Objects 

See the [OpenDxp docs](../19_Development_Tools_and_Details/10_Security_Authentication/01_Authenticate_OpenDxp_Objects.md) 
for how to integrate OpenDxp objects with Symfony Security in general. Here you see how to authenticate against a OpenDxp
user object. 


##### Configure User Permissions with OpenDxp Objects
TBD
