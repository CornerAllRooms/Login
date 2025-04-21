{ pkgs ? import <nixpkgs> {} }:

   pkgs.mkShell {
     buildInputs = [
       pkgs.php82
       pkgs.composer
     ];
   }
