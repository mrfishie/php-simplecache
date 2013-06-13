PHP SimpleCache
===============

A simple PHP script that uses the Memcache class to aid caching and storage of content.

Using SimpleCache
-----------------

After you have required the file, you need to create the SimpleCache object.

    require_once("simplecache.php");
    
    $simplecache = new SimpleCache();

The constructor for SimpleCache has two arguments: host and port. These specify the location for the cache to be stored.
Now, we can start storing information...

    echo = $simplecache->Key("myvar", function() {
        return "Something!";
    });

The `Key` function is the main function in this class. This function has 4 arguments: key, function, expire and compress.

Key is used to set the key where it will be stored. The function must be a callable that will be executed if the cache does not contain the key. By changing expire, you can set how long until the cached object expires. You can use a Unix timestamp or a number of seconds starting from current time. The default for this is 0, which means it will never expire. The final compress parameter sets if we should compress the returned value before storing it.

This function will print out `Something!`. However, by placing an `echo` into the function like below, we can see that the function is actually only called the first time.

    echo = $simplecache->Key("myvar", function() {
        echo "Running the function!";
        return "Something!";
    });

This happens because the code only runs the function once, but then stores the result. This means that if we do something like this:

    function my_func() {
        echo "Running the function!";
        return "Something!";
    });
    
    for ($i = 0; $i < 10; $i++) {
        echo $simplecache->Key("my_func", my_func);
    }

The output will look something like this:

    Running the function!
    Something!
    Something!
    Something!
    Something!
    Something!
    Something!
    Something!
    Something!
    Something!
    Something!
  
This shows how the code actually stores the value, even over multiple requests. If you then run the script again a short time after this, you will see something like this:

    Something!
    Something!
    Something!
    Something!
    Something!
    Something!
    Something!
    Something!
    Something!
    Something!

As you can see, our original function is not actually being called. This is because the return value of it has already been stored. We can return any type from the function, and it will be stored and returned later on.

If the class experiences an error, you can set what it does by using the `SetError()` function, like this:

    $simplecache->SetError(function($error) {
        echo "Oh noes!";
        echo $error;
    });

As you can see, you pass a callable to the `SetError()` function. This callable will be passed a single paramter, a string containing the error, when it is called. By default, the `die()` function will be called.

This is how you can use the SimpleCache class to easily speed up your website.
