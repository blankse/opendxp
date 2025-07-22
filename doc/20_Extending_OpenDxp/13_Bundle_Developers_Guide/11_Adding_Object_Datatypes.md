# Adding Object Datatypes
With plugins, it is also possible to add individual data types for OpenDXP Objects. 
Following steps are necessary to do so: 

1) Create a PHP class for server-side implementation:
 This class needs to extend `OpenDxp\Model\DataObject\ClassDefinition\Data` and defines how your data type is stored into 
  database, how the getters and setters for OpenDXP objects are generated and how data is sent to and read from 
  OpenDXP Admin UI. 
   
   For examples have a look at the OpenDXP core datatypes at 
   [github](https://github.com/open-dxp/opendxp/tree/11.x/models/DataObject/ClassDefinition/Data). 

2) Create JavaScript class for Class Definition editor (object data): 
This JavaScript class defines the representation of the data type in the *Class Definition editor* and therefore where
it is allowed (object, objectbricks, fieldcollections, localizedfields), its group, its label, its icon and its config
options in class editor. 

   It needs to extend `opendxp.object.classes.data.data`, be located in namespace `opendxp.object.classes.data` and named after the 
   `$fieldtype` property of the corresponding PHP class.
     
   For examples have a look at the OpenDXP core datatypes at 
   [github](https://github.com/open-dxp/admin-ui-classic-bundle/tree/1.x/public/js/opendxp/object/classes/data)


3) Create JavaScript class for object editor (object tag):
This JavaScript class defines the representation of the data type in the *object editor* and therefore defines how data
is presented and an can be entered within OpenDXP objects. 

   It needs to extend `opendxp.object.tags.abstract`, be located in namespace `opendxp.object.tags` and named after the 
   `$fieldtype` property of the corresponding PHP class.
     
   For examples have a look at the OpenDXP core datatypes at
   [github](https://github.com/open-dxp/admin-ui-classic-bundle/tree/1.x/public/js/opendxp/object/tags)
   
   
4) Register a datatype in OpenDXP
Register a datatype in OpenDXP by extending the `opendxp.objects.class_definitions.data.map` configuration. 
This can be done in any config file which is loaded (e.g. `config/config.yaml`), but if you provide the datatype 
with a bundle you should define it in a configuration file which is [automatically loaded](./03_Auto_Loading_Config_And_Routing_Definitions.md). 

   Example:

```yaml
# config/config.yaml

opendxp:
    objects:
        class_definitions:
            data:
                map:
                  myDataType: \App\Model\DataObject\Data\MyDataType
```
