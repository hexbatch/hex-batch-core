# API Reference (partial)

## User Accounts

* To get the default sudo user ```> get_default_sudo```

* To make a new sudo user    ```> new_sudo_user ```
  * It will return a guid of a new sudo user
  * All sudo users have full admin rights at first, but another sudo account can restrict  
    
* To make a new regular user  ```> new_regular_user```
  * The new guid will not have any permissions for other user's things
    
* To change permissions of a user ```> chmod {context-user} {user-guid} [srw][+-] {selection text or guid}```
  * Where the context user can give or remove permissions as long as they have the power to do so from the selection
  * and the s = see , r = read , and w = write  only one can be used at a time (as w implies r and s, and r implies s)
  * and the + and - can have one used
  * and the selection can be made previously, or entered as a select text on the command line  
    
One of the guids generated or retrieved above will need to be used in any other command to the library

Users can be iterated through by use of selections, sudo users will have the trait role of **sudo-user** and regular users will have the trait role of **regular_user**

Users can have information sent to a new or existing box trait for them using the regular api to set boxes for mills

## API names

Here, and internally, the api names are in english, but interface hooks can be added to provide alternate names for the api calls. These can only be set using the root interface


```
>add_api_alias !sudo-trait english-api {english-option} language-code api-name {option-name}

>add_api_alias !sudo-trait english-api {english-option} language-code api-name {option-name}
```


When calling the api, to prevent having to always send in a language code, you can set the language code to use (and then enable checking to only use aliases for that language} with


```
>set_default_language_code  language-code
```



## API Help

Then the aliases can be used both in the elements, and on the command line

To see all the api commands


```
>help {language-code}

```



*   if language code is put in, will only show those aliases for that language. If not put in, will be for english
*   will list the name of the command, the fully qualified trait name for it, in that language,  and the guid, and the options


```
>help {language-code|--all-languages} api-name {option-name} {--exists other-language-code} {[--brief|--full|--html|file-name|--list-files]}

```



*   <code>--all-languages</code>
*   Lists for all languages
*   <code>language-code</code>
*   if not set, will use the default language code. Filters the docs for just that language
*   <code>api-name</code>
*   the help for that api name
*   <code>option-name</code>
*   if provided will list the help just for that option
*   <code>--exists</code>
*   when no option name is set, will list all the options and whether that option name, if it has an alias in the first language, also has an alias for the second language <code>other-language-code</code>. If one of the doc formats is also given, will only look to see if that doc format exists for the second language
*   <code>--brief</code>
*   prints the brief text if its set, or empty string if not<code> </code>
*   <code>--full</code>
*   prints the full text if its set, or empty string if not
*   <code>--html</code>
*   prints the html if its set, or empty string if not
*   <code>file-name</code>
*   prints the file contents if that name is set, or empty string
*   <code>--list-files</code>
*   lists all the files by name

Where the --list-files option will list all the files in the docs