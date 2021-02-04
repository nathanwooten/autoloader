# Autoloader Guideline

1. This package ( Profordable packages ) is responsible for providing it's own dependencies ( there are none in this case, but some Profordable packages do have some ).

2. Directory layout for packages will be as follows, the same layout ( for a package ), as seen in a full fledged web application:

- root
    - src
    - vendor

 Each vendor will also contain the same directory layout
 
 - root
    - src
    - vendor

It would be nice if the community and Composer stored packages this way, still relying on Composer for installation and dependencies where needed.

To avoid conflicts, the version of the package could ( maybe should ) be store in the namespace.

For an example of how I might store a package that has dependencies, please see the other Profordable packages.
