# Package Guideline

1. This package ( Profordable packages ) is responsible for providing it's own dependencies ( there are none in this case, but some Profordable packages do have some ).

2. Directory layout for packages will be as follows, the same layout ( for a package ), as seen in a full fledged web application:

- root
    - src
    - vendor

3. Each vendor will also contain the same directory layout as the parent package:
 
 - root
    - src
    - vendor

It would be nice if packages were bundled this way, as well as applications that are ready out of the box, such as Joomla.

To avoid conflicts, the version of the package could ( maybe should ) be stored in the namespace.

[Contact Us](mailto:admin@cloudhadoop.com)

For an example of how I might store a package that has dependencies, please see the other [profordable.com packages](https://github.com/nathanwooten/).
