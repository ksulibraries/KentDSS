KentDSS
=======

Digital Storage System

The Digital Storage System (DSS) provides a centralized location for the medium-term 
storage of digital objects -- known as items. Items can be images, movies, documents, 
or other digital assets, however, only specific file types are permitted.

Items are assigned an expiration date based on the retention period for the item. The 
retention period for an item is based on its assigned Retention Group. A warning message 
is emailed prior to the expiration date so that items can be reviewed before being 
deleted. An item may be identified as a significant item if there is some reason it 
should be stored beyond its expiration date. An item identified as significant will be 
reviewed by the administrator for possible long-term storage prior to its expiration date.

An item belongs to a single project. A project belongs to a single workgroup. Users of 
the system can be members of any number of workgroups. Users can create any number of 
projects and projects can contain any number of items.

Each item has various metadata associated with it. In addition to metadata assigned by 
the system, such as file type and file size, users may provide other metadata about the 
item, such as title, description, creator, creation date, and retention group. The 
metadata for items in a project can be set automatically by uploading a ZIP Archive file. 
Certain metadata, such as creator, can be assigned to every item in a project at one time 
by editing the project information.

See the ARCHITECTURE file for hardware details.
