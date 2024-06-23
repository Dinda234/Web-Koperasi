<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

class NasabahController extends Controller
{
    protected $client;
    protected $apiUrl;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiUrl = 'http://localhost/backend-koperasi/nasabah_api.php'; // Ganti dengan URL endpoint yang sesuai
    }

    public function index()
    {
        try {
            $response = $this->client->get($this->apiUrl);
            $nasabahs = json_decode($response->getBody()->getContents(), true);
            return view('nasabah.index', ['nasabahs' => $nasabahs]);
        } catch (\Exception $e) {
            \Log::error('Error fetching data: ' . $e->getMessage());
            return response()->json(['error' => 'Error fetching data'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $multipart = [
                [
                    'name'     => 'name',
                    'contents' => $request->input('name')
                ],
                [
                    'name'     => 'email',
                    'contents' => $request->input('email')
                ],
                [
                    'name'     => 'password',
                    'contents' => $request->input('password')
                ],
                [
                    'name'     => 'kelamin',
                    'contents' => $request->input('kelamin')
                ],
                [
                    'name'     => 'agama',
                    'contents' => $request->input('agama')
                ],
                [
                    'name'     => 'identitas',
                    'contents' => $request->input('identitas')
                ],
                [
                    'name'     => 'no_identitas',
                    'contents' => $request->input('no_identitas')
                ],
                [
                    'name'     => 'alamat',
                    'contents' => $request->input('alamat')
                ],
                [
                    'name'     => 'no_anggota',
                    'contents' => $request->input('no_anggota')
                ],
                [
                    'name'     => 'status',
                    'contents' => $request->input('status')
                ]
            ];

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $multipart[] = [
                    'name'     => 'foto',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ];
            }

            $response = $this->client->post($this->apiUrl, [
                'multipart' => $multipart
            ]);

            $responseBody = $response->getBody()->getContents();
            \Log::info('API Response: ' . $responseBody);

            $responseData = json_decode($responseBody, true);
            if (is_array($responseData) && isset($responseData['message']) && $responseData['message'] == 'User created successfully') {
                return redirect()->route('nasabah.index')->with('success', 'Nasabah created successfully');
            } else {
                $errorMessage = isset($responseData['message']) ? $responseData['message'] : 'Unknown error';
                return response()->json(['error' => 'Error creating nasabah: ' . $errorMessage], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating nasabah: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $multipart = [
                [
                    'name'     => 'name',
                    'contents' => $request->input('name')
                ],
                [
                    'name'     => 'email',
                    'contents' => $request->input('email')
                ],
                [
                    'name'     => 'kelamin',
                    'contents' => $request->input('kelamin')
                ],
                [
                    'name'     => 'agama',
                    'contents' => $request->input('agama')
                ],
                [
                    'name'     => 'identitas',
                    'contents' => $request->input('identitas')
                ],
                [
                    'name'     => 'no_identitas',
                    'contents' => $request->input('no_identitas')
                ],
                [
                    'name'     => 'alamat',
                    'contents' => $request->input('alamat')
                ],
                [
                    'name'     => 'no_anggota',
                    'contents' => $request->input('no_anggota')
                ],
                [
                    'name'     => 'status',
                    'contents' => $request->input('status')
                ]
            ];

            if ($request->hasFile('foto')) {
                $file = $request->file('foto');
                $multipart[] = [
                    'name'     => 'foto',
                    'contents' => fopen($file->getPathname(), 'r'),
                    'filename' => $file->getClientOriginalName()
                ];
            }

            $response = $this->client->put($this->apiUrl . '?id=' . $id, [
                'multipart' => $multipart
            ]);

            $responseBody = $response->getBody()->getContents();
            \Log::info('API Response: ' . $responseBody);

            $responseData = json_decode($responseBody, true);
            if (is_array($responseData) && isset($responseData['message']) && $responseData['message'] == 'User updated successfully') {
                return redirect()->route('nasabah.index')->with('success', 'Nasabah updated successfully');
            } else {
                $errorMessage = isset($responseData['message']) ? $responseData['message'] : 'Unknown error';
                return response()->json(['error' => 'Error updating nasabah: ' . $errorMessage], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating nasabah: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $response = $this->client->delete($this->apiUrl . '?id=' . $id);
            return redirect()->route('nasabah.index')->with('success', 'Nasabah deleted successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting nasabah'], 500);
        }
    }
}
