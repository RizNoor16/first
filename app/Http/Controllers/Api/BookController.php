<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers;

use Illuminate\Http\Request;
use app\Models\Book;

class BookController extends Controller {

    public function getAllBooks() {
      die("eee");
      $books = Book::where('id', '>', 1)->simplePaginate(1);
      die($books);
        return response($books, 200);
      }

    public function addBook(Request $request) {
        $books= new Books();
        $books->name = $request->name;
        $books->author = $request->author;
        $books->save();

        return response()->json([
            "message" => "Book record added successfully"
          ], 201);
        }

    public function updateBook(Request $request, $id) {
        if (Book::where('id', $id)->exists()) {
            $book = Book::find($id);
      
            $book->name = is_null($request->name) ? $book->name : $book->name;
            $book->author = is_null($request->author) ? $book->author : $book->author;
            $book->save();
      
            return response()->json([
                "message" => "records updated successfully"
              ], 200);
            } else {
            return response()->json([
                "message" => "Book not found"
              ], 404);
            }
        }

    public function getBook($id) {
        if (Book::where('id', $id)->exists()) {
            $book = Book::where('id', $id)->get()->toJson(JSON_PRETTY_PRINT);
            return response($book, 200);
            } else {
            return response()->json([
                "message" => "Book not found"
              ], 404);
            }
          }

    public function deleteBook ($id) {  
        if(Book::where('id', $id)->exists()) {
              $book = Book::find($id);
              $book->delete();
      
            return response()->json([
                "message" => "records deleted"
              ], 202);
            } else {
            return response()->json([
                "message" => "Book not found"
              ], 404);
            }
          }
}